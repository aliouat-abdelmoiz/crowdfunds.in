@extends('user')
@section('main-content')
    <div class="col-md-10">
        <table class="table table-striped pages">
            <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Message</th>
            </tr>
            </thead>
            <tbody>
            {!! $conversations->render() !!}
            @if(count($conversations) > 0)
                @foreach($conversations as $conversation)
                    <tr class="clickable pageitem {{ $conversation->getTotalMessages($conversation->id) != 0 ? 'bold' : 'blank' }}"
                        data-message="{{ $conversation->id }}">
                        <td><input type="checkbox" value="{{ $conversation->id }}" class="check"
                                   name="messages[]"></span>
                        </td>
                        <td>
                            <span>{{ $conversation->id }}</span>
                        </td>
                        <td>
                            <span>{{ $conversation->subject }}</span>
                        </td>
                        <td colspan="2"><span>{{ $conversation->updated_at->diffForHumans() }}</span>
                        </td>
                        @if($conversation->getTotalMessages($conversation->id) == 0)
                            <td><span class="fa fa-check unread"></span></td>
                        @else
                            <td>&nbsp;</td>
                        @endif
                        <td class="text-right">
                            {!! $conversation->getTotalMessages($conversation->id) == 0 ? '<span
                                    class="red">No Message</span>' : '<span class="green">' . $conversation->getTotalMessages($conversation->id) . '&nbsp;New <span
                                        class="fa fa-inbox"></span></span>' !!}
                        </td>
                        <td class="text-right">
                            {{ $conversation->user[0]->name == Auth::user()->name ? $conversation->user[1]->name : $conversation->user[0]->name }}
                        </td>
                    </tr>
                @endforeach
            @else
                <h1>Empty</h1>
            @endif
            </tbody>
        </table>
    </div>

@section('script')

    <script>
        $("document").ready(function () {
            $('.clickable').click(function () {
                $message = $(this).data("message");
                $.ajax('/markAsRead', {
                    data: {
                        opened: 1,
                        conversation_id: $message,
                        user_id: "{{ Auth::id() }}",
                    },
                    success: function () {
                        return window.location.href = "/message/" + $message;
                    }
                });
            })
        })
    </script>

@endsection
@endsection