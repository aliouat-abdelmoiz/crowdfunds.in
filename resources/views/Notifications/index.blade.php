@extends('user')

@section('main-content')
    <div class="col-md-10">
        <table class="table table-striped pages">
            <thead>
            <tr>
                <th>When</th>
                <th>Title</th>
                <th>Client</th>
            </tr>
            </thead>
            <tbody>
            @if(count($notifications) > 0)
                @foreach($notifications as $notification)
                    <tr class="clickable {{ $notification->read == 0 ? "bold" : "blank" }} " data-link="{{ $notification->link }}" data-notify="{{ $notification->id }}">
                        <td>{{ $notification->created_at->diffForHumans() }}</td>
                        <td>{{ $notification->text }}</td>
                        
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script>
        $(".clickable").click(function() {
            $that = $(this);
            $.ajax({
                url: '/api/makereadnotify',
                method: 'POST',
                data: {
                    notification_id: $that.data('notify'),
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $.ajax({
                        url: '/notifications/' + $that.data('notify'),
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        }
                    });
                    window.location.href = $that.data('link');
                }
            });
        })
    </script>
@endsection