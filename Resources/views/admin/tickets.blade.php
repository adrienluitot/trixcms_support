@extends('Plugins.Support_alfiory__930442654.Resources.views.layouts.admin')


@section('content')
    <div class="row">
        <div class="col-md-3 col-sm6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{trans('support_alfiory::admin.unread_tickets_count')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$unreadTickets}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{trans('support_alfiory::admin.read_tickets_count')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$readTickets}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{trans('support_alfiory::admin.resolved_tickets_count')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$resolvedTicketsCount}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{trans('support_alfiory::admin.total_tickets_count')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tickets->count()}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-th-list"></i> {{trans('support_alfiory::admin.tickets_list')}}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="support-tickets-list">
                    <thead>
                        <tr>
                            <th>{{trans('support_alfiory::admin.id')}}</th>
                            <th>{{trans('support_alfiory::admin.subject')}}</th>
                            <th>{{trans('support_alfiory::admin.state')}}</th>
                            <th>{{trans('support_alfiory::admin.category')}}</th>
                            <th>{{trans('support_alfiory::admin.tags')}}</th>
                            <th>{{trans('support_alfiory::admin.member')}}</th>
                            <th>{{trans('support_alfiory::admin.read')}}</th>
                            <th>{{trans('support_alfiory::admin.manage')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script>
        let tickets = [];
        @foreach($tickets as $ticket)
            tickets.push([
                '# {{$ticket->id}}', // id
                '{{Str::limit($ticket->subject, 70, '...')}}', // subject
                '{!! (!$ticket->resolved)? trans('support_alfiory::admin.opened') . ' <i class="fas fa-lock-open"></i>' : trans('support_alfiory::admin.closed') . ' <i class="fas fa-lock"></i>' !!}', // state
                '<span class="label" style="background: #{{$ticket->category_color}}">{{$ticket->category_name}}</span>', // category
                @if($ticket->tags)
                    @foreach(json_decode($ticket->tags, true) as $tagId => $tag)
                        '<span class="tag" style="background: #{{$tag[1]}};">{{$tag[0]}}</span>' +
                    @endforeach
                    '', // tags
                @else
                    '<em>{{trans('support_alfiory::admin.none')}}</em>',
                @endif
                '{{$ticket->member_pseudo}}', // member
                '{!! ($ticket->seen)? trans('support_alfiory::admin.read') . ' <i class="fas fa-low-vision"></i>' : trans('support_alfiory::admin.unread') . ' <i class="far fa-eye"></i>' !!}', // read
                '<a href="{{route('admin.support_alfiory.view_ticket', ['id' => $ticket->id])}}"><i class="fas fa-external-link-square-alt"></i></a>' //manage
            ]);
        @endforeach

        $(document).ready( function () {
            $('#support-tickets-list, #support-priority-tickets-first').DataTable({
                data: tickets,
                order: [
                    [2, '{{trans('support_alfiory::admin.datatable_translation_state_order')}}'],
                    [6, '{{trans('support_alfiory::admin.datatable_translation_read_order')}}'],
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data[2] == '{{trans("support_alfiory::admin.closed")}} <i class="fas fa-lock"></i>') {
                        $(row).addClass('closed-ticket');
                    } else if (data[6] == '{{trans('support_alfiory::admin.read')}} <i class="fas fa-low-vision"></i>') {
                        $(row).addClass('read-ticket');
                    }
                },
                language: {
                    "url": "@PluginAssets('datatables_translations')::{{trans('support_alfiory::admin.datatable_translation_file')}}"
                    // full list here : https://datatables.net/plug-ins/i18n/
                }
            });
        } );
    </script>
@endsection