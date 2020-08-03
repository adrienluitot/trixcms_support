<link rel="stylesheet" href="@PluginAssets('css/style.css')">

<div id="page" class="container">
    <div class="row support-container">
        <div class="col-12 mb-5">
            @if(session('support_flash'))
                <div class="alert alert-{{session('support_flash')['state']}}">
                    {{session('support_flash')['message']}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <h2>{{trans('support_alfiory::user.opened_tickets')}}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{trans('support_alfiory::user.category')}}</th>
                        <th>{{trans('support_alfiory::user.subject')}}</th>
                        <th>{{trans('support_alfiory::user.creation_date')}}</th>
                        <th>{{trans('support_alfiory::user.read')}}</th>
                        <th>{{trans('support_alfiory::user.manage')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($openedTickets as $ticket)
                        <tr{!! (!isset($ticket->seen) || $ticket->seen)? ' class="read"':'' !!}>
                            <td>{{$ticket->category_name}}</td>
                            <td>{{$ticket->subject}}</td>
                            <td>{{$ticket->created_at}}</td>
                            <td>{!! (!isset($ticket->seen) || $ticket->seen)? trans('support_alfiory::admin.read') . ' <i class="fas fa-low-vision"></i>' : trans('support_alfiory::admin.unread') . ' <i class="far fa-eye"></i>' !!}</td>
                            <td>
                                <a href="{{route('support_alfiory.ticket', ['id' => $ticket->id])}}"><i class="fas fa-external-link-square-alt"></i></a>
                                <a class="resolve-ticket" data-ticket-id="{{$ticket->id}}" title="{{trans('support_alfiory::user.resolve_ticket')}}"><i class="fas fa-thumbs-up"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    @if(!sizeof($openedTickets))
                        <tr>
                            <td colspan="5" style="text-align: center;font-style: italic;">{{trans("support_alfiory::user.no_opened_ticket")}}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="text-center">
                <a href="{{route('support_alfiory.new_ticket')}}" class="btn btn-md btn-info">{{trans('support_alfiory::user.open_new_ticket')}}</a>
            </div>
        </div>

        <div class="col-12">
            <h2>{{trans('support_alfiory::user.resolved_tickets')}}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{trans('support_alfiory::user.category')}}</th>
                        <th>{{trans('support_alfiory::user.subject')}}</th>
                        <th>{{trans('support_alfiory::user.creation_date')}}</th>
                        <th>{{trans('support_alfiory::user.display')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($closedTickets as $ticket)
                        <tr>
                            <td>{{$ticket->category_name}}</td>
                            <td>{{$ticket->subject}}</td>
                            <td>{{$ticket->created_at}}</td>
                            <td><a href="{{route('support_alfiory.ticket', ['id' => $ticket->id])}}"><i class="fas fa-external-link-square-alt"></i></a></td>
                        </tr>
                    @endforeach
                    @if(!sizeof($closedTickets))
                        <tr>
                            <td colspan="5" style="text-align: center;font-style: italic;">{{trans("support_alfiory::user.no_closed_ticket")}}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('.resolve-ticket').on('click', function () {
        if(confirm("{{trans('support_alfiory::user.resolve_ticket_confirmation')}}")) {
            let id = $(this).data("ticket-id");

            $.ajax({
                url: "{{route('support_alfiory.resolve_ticket')}}",
                type: "post",
                dataType: "html",
                data: 'id=' + id,
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                },
                success: () => {
                    window.location.replace('{{route("support_alfiory.home")}}');
                },
            });
        }
    });
</script>
