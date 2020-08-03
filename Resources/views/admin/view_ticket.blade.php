@extends('Plugins.Support_alfiory__930442654.Resources.views.layouts.admin')


@section('content')
    <a href="{{route('admin.support_alfiory.tickets')}}"><i class="fas fa-chevron-left"></i> {{trans("support_alfiory::admin.back_to_tickets")}}</a>

    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-cogs"></i> {{trans('support_alfiory::admin.manage_ticket')}} {{$ticket->subject}}</h6>
        </div>
        <div class="card-body">
            <h5 class="mb-2">{{trans("support_alfiory::admin.information")}}:</h5>
            <div class="col-12">
                <span><b>{{trans('support_alfiory::admin.member')}}:</b> <a href="{{route('dashboard.admin.personalization.user', ['id' => $ticket->member_id])}}">{{$ticket->member_alias}}</a></span><br />
                <span><b>{{trans('support_alfiory::admin.subject')}}:</b> {{$ticket->subject}}</span><br />
                <span><b>{{trans('support_alfiory::admin.category')}}:</b> <span class="label" style="background: #{{$ticket->category_color}};">{{$ticket->category_name}}</span></span><br />
                <span><b>{{trans('support_alfiory::admin.open_date')}}:</b> {{$ticket->created_at}}</span><br />
                <span><b>{{trans('support_alfiory::admin.last_update_date')}}:</b> {{$ticketMessages[sizeof($ticketMessages)-1]->created_at}}</span><br />
                <span><b>{{trans('support_alfiory::admin.state')}}:</b> {!! (!$ticket->resolved)? trans('support_alfiory::admin.opened') . ' <i class="fas fa-lock-open"></i>' : trans('support_alfiory::admin.closed') . ' <i class="fas fa-lock"></i>' !!}</span>
            </div>

            <h5 class="mt-3">{{trans("support_alfiory::admin.edit")}}:</h5>
            <div class="col-12">
                <div class="col-12">
                    <h6>{{trans("support_alfiory::admin.tags")}}:</h6>
                    <div class="tags">
                        @if($ticket->tags)
                            @foreach(json_decode($ticket->tags, true) as $tagId => $tag)
                                <span class="tag" style="background: #{{$tag[1]}};">
                                    {{$tag[0]}}
                                    @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_TICKET|admin')) <a data-tag-id="{{$tagId}}" class="delete-tag">&times;</a> @endif
                                </span>
                            @endforeach
                        @endif
                    </div>
                    @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_TICKET|admin'))
                        <div class="row mt-3" id="add-tag-container">
                            <div class="form-group col-md-4">
                                <input class="form-control" id="name" placeholder="{{trans("support_alfiory::admin.name")}}">
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="text" id="color" class="form-control" placeholder="{{trans('support_alfiory::admin.color')}}">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <button class="btn btn-success" id="add-ticket-tag"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    @endif
                </div>

                @if(user()->hasPermission('DASHBOARD_SUPPORT_DELETE_TICKET|DASHBOARD_SUPPORT_ANSWER_TICKET|admin'))
                    <div class="col-12 mt-2">
                        <h6>{{trans("support_alfiory::admin.actions")}}:</h6>
                        @if(user()->hasPermission('DASHBOARD_SUPPORT_ANSWER_TICKET|admin'))
                            @if($ticket->resolved)
                                <a href="{{route("admin.support_alfiory.change_resolved", ['id' => $ticket->id])}}" class="btn btn-warning">{{trans("support_alfiory::admin.reopen")}} <i class="fas fa-lock-open"></i></a>
                            @else
                                <a href="{{route("admin.support_alfiory.change_resolved", ['id' => $ticket->id])}}"  class="btn btn-warning">{{trans("support_alfiory::admin.close")}} <i class="fas fa-lock"></i></a>
                            @endif
                        @endif

                        @if(user()->hasPermission('DASHBOARD_SUPPORT_DELETE_TICKET|admin'))
                            <button id="delete-ticket" class="btn btn-danger">{{trans("support_alfiory::admin.delete")}} <i class="fas fa-trash"></i></button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-ticket-alt"></i> {{trans('support_alfiory::admin.messages')}}</h6>
        </div>
        <div class="card-body">
            <div class="ticket-messages">
                @foreach($ticketMessages as $message)
                    <div class="ticket-message col-11 col-md-9 {{ ($message->member_id != $ticket->member_id)? 'offset-1 offset-md-3 admin' : '' }}">
                        <p>{{$message->member_alias}} - <small>{{$message->created_at}}</small></p>
                        <p><?= nl2br(e($message->content)); ?></p>
                    </div>
                @endforeach
            </div>

            @if(user()->hasPermission('DASHBOARD_SUPPORT_ANSWER_TICKET|admin') && !$ticket->resolved)
                <hr>

                <div class="writing-zone">
                    <form method="post" action="">
                        @csrf

                        <div class="form-group col-12">
                            <label>{{trans('support_alfiory::admin.answer')}}</label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" placeholder="{{trans('support_alfiory::admin.answer')}}" name="answer">{{old('answer')}}</textarea>
                            @error('answer') <div class="invalid-feedback">{{$message}}</div> @enderror
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">{{trans("support_alfiory::admin.send")}}</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_TICKET|admin'))
            // ADD TAG
            $('#add-ticket-tag').on('click', () => {
                $("#add-ticket-tag").prop('disabled', true);
                $.ajax({
                    url: '{{route('admin.support_alfiory.add_tag', ['id' => $ticket->id])}}',
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                    },
                    data: 'name=' + $('#name').val() + '&color=' + $('#color').val(),
                    success: (data) => {
                        $('#add-tag-container .invalid-feedback').remove();
                        $(".tags").append('<span class="tag" style="background: #'+$('#color').val().replace("#", "")+';">'+$('#name').val()+'<a data-tag-id="'+data.id+'" class="delete-tag">&times;</a></span>');

                        $("#alert-container").html('<div class="alert alert-success" id="support-alert">' +
                                                        data.message +
                                                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                        '</button>' +
                                                    '</div>');

                        $('#add-tag-container input').removeClass("is-invalid").val("");
                        $("#add-ticket-tag").prop('disabled', false);
                    },
                    error: (data) => {
                        let errors = data.responseJSON.errors;
                        $('#add-tag-container .invalid-feedback').remove();
                        $('#add-tag-container input').removeClass("is-invalid");

                        for(let error in errors) {
                            let input = $('#' + error);
                            input.addClass('is-invalid');
                            input.parent().append('<span class="invalid-feedback">'+errors[error]+'</span>');
                        }
                        $("#add-ticket-tag").prop('disabled', false);
                    }
                });
            });

            $('.tags').on('click', '.delete-tag', function () {
                let tagId = $(this).data("tag-id");

                $.ajax({
                    url: '{{route('admin.support_alfiory.delete_tag', ['id' => $ticket->id])}}',
                    type: "post",
                    data: 'id=' + tagId,
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                    },
                    success: (data) => {
                        $(".delete-tag[data-tag-id='" + tagId + "']").parent().remove();

                        $("#alert-container").html('<div class="alert alert-success" id="support-alert">' +
                                                        data.message +
                                                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                        '</button>' +
                                                    '</div>');
                    }
                });
            });
        @endif

        @if(user()->hasPermission('DASHBOARD_SUPPORT_DELETE_TICKET|admin'))
            // DELETE TICKET
            $("#delete-ticket").on('click', function () {
                console.log("test");
                if(confirm("{{trans('support_alfiory::admin.delete_confirmation')}}")) {
                    window.location.replace("{{route('admin.support_alfiory.delete_ticket', ['id' => $ticket->id])}}");
                }
            });
        @endif
    </script>
@endsection