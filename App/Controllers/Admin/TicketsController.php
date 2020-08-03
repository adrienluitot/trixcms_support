<?php

namespace Extensions\Plugins\Support_alfiory__930442654\App\Controllers\Admin;

use Illuminate\Http\Request;
use App\System\Extensions\Plugin\Core\PluginController as AdminController;
use Extensions\Plugins\Support_alfiory__930442654\App\Models\SupportAlfioryTicket as SupportTicket;
use Extensions\Plugins\Support_alfiory__930442654\App\Models\SupportAlfioryTicketsMessage as SupportTicketsMessage;
use Illuminate\Support\Facades\DB;

class TicketsController extends AdminController
{
    public $admin = true;

    public function index ()
    {

        $tickets = SupportTicket::join('users', 'support_alfiory__tickets.member_id', '=', 'users.id')
            ->join('support_alfiory__categories', 'support_alfiory__tickets.category_id', '=', 'support_alfiory__categories.id')
            ->join(DB::raw('(SELECT ticket_id, MAX(created_at) as MaxTime, member_id FROM support_alfiory__tickets_messages GROUP BY ticket_id, member_id) as assoc'), function ($join) {
                $join->on('support_alfiory__tickets.id', '=', 'assoc.ticket_id')
                    ->on('support_alfiory__tickets.member_id', '=', 'assoc.member_id');
            })
            ->join('support_alfiory__tickets_messages', function ($join) {
                $join->on('support_alfiory__tickets_messages.created_at', '=', 'assoc.MaxTime')
                    ->on('support_alfiory__tickets_messages.ticket_id', '=', 'support_alfiory__tickets.id');
            })
            ->select('support_alfiory__tickets.*', 'users.pseudo as member_pseudo', 'support_alfiory__categories.name as category_name', 'support_alfiory__categories.color as category_color', 'support_alfiory__tickets_messages.seen as seen')->get();

        $resolvedTicketsCount = SupportTicket::where('resolved', 1)->count();
        $unreadTickets = 0;
        $readTickets = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->seen) {
                $readTickets++;
            } else {
                $unreadTickets++;
            }
        }

        return $this->view(
            'admin.tickets',
            trans('support_alfiory::admin.support') . ' - ' . trans('support_alfiory::admin.tickets'),
            compact('tickets', 'resolvedTicketsCount', 'readTickets', 'unreadTickets')
        );
    }

    public function view_ticket($id) {
        $ticket = SupportTicket::where('support_alfiory__tickets.id', '=', $id)
            ->join('users', 'support_alfiory__tickets.member_id', '=', 'users.id')
            ->join('support_alfiory__categories', 'support_alfiory__tickets.category_id', '=', 'support_alfiory__categories.id')
            ->select('support_alfiory__tickets.*', 'users.pseudo as member_alias', 'users.id as member_id', 'support_alfiory__categories.name as category_name', 'support_alfiory__categories.color as category_color')->first();

        SupportTicketsMessage::where(['ticket_id' => $ticket->id, 'member_id' => $ticket->member_id])->update(['seen' => 1, 'updated_at' => now()]);

        $ticketMessages = SupportTicketsMessage::where('ticket_id', $ticket->id)
            ->join('users', 'support_alfiory__tickets_messages.member_id', '=', 'users.id')
            ->select('support_alfiory__tickets_messages.*', 'users.pseudo as member_alias', 'users.id as member_id')
            ->orderBy('created_at', 'asc')->get()->all();

        return $this->view(
            'admin.view_ticket',
            trans('support_alfiory::admin.view_ticket') . ' - ' . trans('support_alfiory::admin.tickets'),
            compact('ticket', 'ticketMessages')
        );
    }

    public function answer_ticket(Request $request, $id) {
        $request->validate([
            'answer' => ['required', 'string', 'min: 10'],
        ],
        [
            'answer.required' => trans('support_alfiory::admin.required'),
            'answer.min' => trans('support_alfiory::admin.field_string_too_short'),
        ]);

        SupportTicketsMessage::insert([
            'member_id' => user()->id,
            'ticket_id' => $id,
            'content' => $request->answer,
            'seen' => 0,
            'created_at' => now()
        ]);

        $ticketInfo = SupportTicket::findOrFail($id);

        $this->model()->instancy('UserNotifications')->addNotification($ticketInfo->member_id, trans('support_alfiory::admin.ticket_answered_notification', ['subject' => $ticketInfo->subject]));

        session()->flash("support_flash", ['state' => 'success', 'message' => trans('support_alfiory::admin.answer_made')]);
        return redirect()->back();
    }

    public function add_tag(Request $request, $id) {
        $request->validate([
            "name" => ['required', 'string', 'max:255'],
            "color" => ['required', 'string', 'regex:/^#?([0-9a-f]{3}){1,2}$/i']
        ],
        [
            "name.required" => trans('support_alfiory::admin.required'),
            "name.max" => trans('support_alfiory::admin.field_too_long'),
            "color.required" => trans('support_alfiory::admin.required'),
            "color.regex" => trans('support_alfiory::admin.color_wrong_format')
        ]);

        $tags = json_decode(SupportTicket::findOrFail($id)->tags, true);
        if($tags) {
            $futureTagId = max(array_keys($tags)) + 1;
        } else {
            $futureTagId = 0;
        }
        $tags[$futureTagId] = [$request->name, str_replace("#", "", $request->color)];

        SupportTicket::where('id', '=', $id)->first()->update([
           'tags' => json_encode($tags),
            'updated_ar' => now()
        ]);

        return ['state' => 'success', 'message' => trans('support_alfiory::admin.tag_added'), 'id' => $futureTagId];
    }

    public function delete_tag (Request $request, $id) {
        $ticket = SupportTicket::findOrFail($id);
        $tags = json_decode($ticket->tags, true);

        $request->validate([
            'id' => ['required', 'integer']
        ]);

        unset($tags[$request->id]);

        $ticket->update([
           'tags' => json_encode($tags),
            'updated_at' => now()
        ]);

        return ['message' => trans('support_alfiory::admin.tag_deleted')];
    }

    public function change_resolved ($id) {
        $ticket = SupportTicket::findOrFail($id);

        $ticket->update([
            'resolved' => ($ticket->resolved)? 0 : 1,
            'updated_at' => now()
        ]);

        session()->flash('support_flash', ['state' => 'success', 'message' => ($ticket->resolved)? trans('support_alfiory::admin.ticket_mark_as_unresolved') : trans('support_alfiory::admin.ticket_mark_as_resolved')]);
        return redirect()->back();
    }

    public function delete_ticket ($id) {
        SupportTicket::findOrFail($id)->delete();
        SupportTicketsMessage::where('ticket_id', '=', $id)->delete();

        session()->flash('support_flash', ['state' => 'success', 'message' => trans('support_alfiory::admin.ticket_deleted')]);
        return redirect()->to(route('admin.support_alfiory.tickets'));
    }
}
