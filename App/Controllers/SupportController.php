<?php

namespace Extensions\Plugins\Support_alfiory__930442654\App\Controllers;

use Illuminate\Http\Request;
use App\System\Extensions\Plugin\Core\PluginController as Controller;
use Extensions\Plugins\Support_alfiory__930442654\App\Models\SupportAlfioryTicket as SupportTicket;
use Extensions\Plugins\Support_alfiory__930442654\App\Models\SupportAlfioryCategory as SupportCategory;
use Extensions\Plugins\Support_alfiory__930442654\App\Models\SupportAlfioryTicketsMessage as SupportTicketsMessage;
use App\Http\Traits\App;
use Illuminate\Support\Facades\DB;



class SupportController extends Controller
{
    use App;

    public function index() {
        $openedTickets = SupportTicket::where(['support_alfiory__tickets.member_id' => user()->id, 'resolved' => 0])
            ->join('support_alfiory__categories', 'support_alfiory__tickets.category_id', '=', 'support_alfiory__categories.id')
            ->leftJoin(DB::raw('(SELECT ticket_id, MAX(created_at) as MaxTime, member_id FROM support_alfiory__tickets_messages GROUP BY ticket_id, member_id) as assoc'), function ($join) {
                $join->on('support_alfiory__tickets.id', '=', 'assoc.ticket_id')
                    ->on('support_alfiory__tickets.member_id', '<>', 'assoc.member_id');
            })
            ->leftJoin('support_alfiory__tickets_messages', function ($join) {
                $join->on('support_alfiory__tickets_messages.created_at', '=', 'assoc.MaxTime')
                    ->on('support_alfiory__tickets_messages.ticket_id', '=', 'support_alfiory__tickets.id');
            })
            ->select('support_alfiory__tickets.*', 'support_alfiory__categories.name as category_name', 'support_alfiory__tickets_messages.seen')->get();

        $closedTickets = SupportTicket::where(['support_alfiory__tickets.member_id' => user()->id, 'resolved'=> 1])
            ->join('support_alfiory__categories', 'support_alfiory__tickets.category_id', '=', 'support_alfiory__categories.id')
            ->select('support_alfiory__tickets.*', 'support_alfiory__categories.name as category_name')->get();

        return $this->view(
            'home',
            trans('support_alfiory::user.support'),
            compact('openedTickets', 'closedTickets')
        );
    }

    public function new_ticket () {
        $categories = SupportCategory::get();

        return $this->view(
            'new_ticket',
            trans('support_alfiory::user.support'),
            compact('categories')
        );
    }

    public function create_ticket (Request $request) {
        $request->validate([
            'category' => ['required', 'integer'],
            'subject' => ['required', 'string', 'min:5', 'max:255'],
            'message' => ['required', 'string', 'min: 30'],
        ],
        [
            'category.required' => trans('support_alfiory::user.field_required'),
            'subject.required' => trans('support_alfiory::user.field_required'),
            'subject.min' => trans('support_alfiory::user.field_string_too_short'),
            'subject.max' => trans('support_alfiory::user.field_string_too_long'),
            'message.required' => trans('support_alfiory::user.field_required'),
            'message.min' => trans('support_alfiory::user.field_string_too_short'),
        ]);

        SupportCategory::findOrFail($request->category);

        $ticketId = SupportTicket::insertGetId([
            'member_id' => user()->id,
            'category_id' => $request->category,
            'tags' => NULL,
            'subject' => $request->subject,
            'resolved' => 0,
            'created_at' => now()
        ]);

        SupportTicketsMessage::insert([
            'member_id' => user()->id,
            'ticket_id' => $ticketId,
            'content' => $request->message,
            'seen' => 0,
            'created_at' => now()
        ]);

        $this->model()->instancy('DashBoardNotifs')->addNotification(trans('support_alfiory::user.new_ticket', ['alias' => user()->pseudo, 'subject' => $request->subject]));

        session()->flash("support_flash", ["state" => "success", "message" => trans("support_alfiory::user.ticket_opened")]);
        return redirect()->to(route("support_alfiory.home"));
    }

    public function ticket($id) {
        $ticket = SupportTicket::findOrFail($id);

        if($ticket->member_id != user()->id) abort(403);

        SupportTicketsMessage::where(['ticket_id' => $ticket->id, ['member_id', '<>', user()->id]])->update(['seen' => 1, 'updated_at' => now()]);

        $ticketMessages = SupportTicketsMessage::where('ticket_id', $ticket->id)
            ->join('users', 'support_alfiory__tickets_messages.member_id', '=', 'users.id')
            ->select('support_alfiory__tickets_messages.*', 'users.pseudo')->get()->all();

        return $this->view(
            'ticket',
            trans('support_alfiory::user.support'),
            compact('ticket', 'ticketMessages')
        );
    }

    public function send_message(Request $request, $id) {
        $ticket = SupportTicket::findOrFail($id);
        if($ticket->member_id != user()->id) abort(403);
        if($ticket->resolved) redirect()->to(route('support_alfiory.home'));

        $request->validate([
            'answer' => ['required', 'string', 'min: 30'],
        ],
        [
            'answer.required' => trans('support_alfiory::user.field_required'),
            'answer.min' => trans('support_alfiory::user.field_string_too_short'),
        ]);

        SupportTicketsMessage::insert([
            'member_id' => user()->id,
            'ticket_id' => $id,
            'content' => $request->answer,
            'seen' => 0,
            'created_at' => now()
        ]);

        session()->flash("support_flash", ['state' => 'success', 'message' => trans('support_alfiory::user.answer_made')]);
        return redirect()->back();
    }

    public function resolve_ticket(Request $request) {
        $ticket = SupportTicket::findOrFail($request->id);
        if($ticket->member_id != user()->id) abort(403);

        $ticket->update(['resolved' => 1, 'updated_at' => now()]);
        return true;
    }
}
