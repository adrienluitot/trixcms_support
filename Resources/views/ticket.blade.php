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

            <h2>{{trans('support_alfiory::user.ticket')}} <em>{{$ticket->subject}}</em></h2>
            <a class="mb-4 d-block" style="color: #007bff;" href="{{route('support_alfiory.home')}}">{{trans('support_alfiory::user.return_to_tickets')}}</a>


            @if($ticket->resolved)
                <div class="alert alert-warning mb-4">
                    {{trans('support_alfiory::user.ticket_has_been_resolved')}}
                </div>
            @endif

            <div class="ticket-messages">
            @foreach($ticketMessages as $message)
                <div class="ticket-message col-11 col-md-9 {{ ($message->member_id == user()->id)? 'offset-1 offset-md-3 owner' : '' }}">
                    <p><b>{{($message->member_id == user()->id)? trans('support_alfiory::user.you') : $message->pseudo}}</b> - <span class="message-posted-date">{{$message->created_at}}</span></p>
                    <p><?= nl2br(e($message->content)); ?></p>
                </div>
            @endforeach
            </div>

            @if(!$ticket->resolved)
                <div class="writing-zone mt-3">
                    <form method="post" action="">
                        @csrf

                        <div class="form-group col-12">
                            <label>{{trans('support_alfiory::user.answer')}}</label>
                            <textarea name="answer" placeholder="{{trans('support_alfiory::user.answer')}}" class="form-control @error('answer') is-invalid @enderror">{{old('answer')}}</textarea>
                            @error('answer') <div class="invalid-feedback">{{$message}}</div> @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-info">{{trans('support_alfiory::user.send')}}</button>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="button" id="resolve-ticket" class="btn btn-success">{{trans('support_alfiory::user.resolve_ticket')}}</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@if(!$ticket->resolved)
    <script>
        $('#resolve-ticket').on('click', function () {
            if(confirm("{{trans('support_alfiory::user.resolve_ticket_confirmation')}}")) {
                $.ajax({
                    url: "{{route('support_alfiory.resolve_ticket')}}",
                    type: "post",
                    dataType: "html",
                    data: 'id={{$ticket->id}}',
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
@endif