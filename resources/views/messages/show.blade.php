@extends('user')
@section('main-content')
    <div class="col-md-10">
        @if(isset($messages))
            {!! $messages->render() !!}
            <ul class="message-list pages">
                @if($messages->isEmpty())
                    <h1>No Message</h1>
                @else
                    @foreach($messages as $message)
                        <li class="messages clearfix pageitem">
                            <div class="image">
                                @if($message->user->hasRole('Provider'))
                                    <a href="/profile/{{ $message->user->provider->id }}">
                                        <img class="img-circle" onerror="imgError(this)"
                                             src="{{ $message->user->avatar == "" ? "/uploads/users/logos/" . $message->user->provider->logo : $message->user->avatar}}"
                                             alt=""></a>
                                @else
                                    <img class="img-circle" onerror="imgError(this)"
                                         src="{{ \App\User::find(Auth::id())->hasRole('Provider') ? '/uploads/users/logos/' . Auth::user()->provider->logo : Auth::user()->avatar }}"
                                         alt="">
                                @endif
                            </div>
                            <div class="message-section">
                                <a href="#message"><h5>{{ $message->user->name }}</h5></a>
                                <p id="message-{{ $message->id }}">{{ $message->content }}</p>
                                <small><p class="pull-left">Sent
                                        by {{ $message->user->name }} {{ $message->created_at->diffForHumans() }}</p>
                                </small>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="container-box">
                <p class="text-center"><a href="#" class="nextItems btn btn-link">Load More Messages</a></p>
                <h4 class="message-send-title">Send Message</h4>
                <textarea name="message" id="message" rows="10"></textarea>
                <input type="button" data-reply="{{ $conv_id }}" class="send_reply btn btn-success"
                       value="Send Message">
            </div>
        @else
            <h4>Empty Message Box</h4>
        @endif
    </div>
@section('script')
    <script>
        $("document").ready(function () {
            $(".pages").infinitescroll('unbind');
            $('.nextItems').click(function () {
                if ($(".end-msg").length == 0) {
                    $('.pages').infinitescroll('retrieve');
                } else {
                    $(this).fadeOut();
                }
                return false;
            });
            $(".send_reply").click(function () {
                $(".send_reply").val("Loading...");
                $.ajax({
                    url: '/api/user/replymessage',
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        content: $("#message").val(),
                        conversation_id: $(this).data("reply")
                    },
                    success: function () {
                        window.location.reload();
                    }
                });
            })
        })
    </script>
@endsection
@endsection

