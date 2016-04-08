@extends('user')
@section('content')
    <h3>Activate Plans</h3><br>
    <table class="table table-striped">
        <tr>
            <td><b>Plan Type</b></td>
            <td><b>Active/Inactive</b></td>
            <td><b>Price</b></td>
            <td><b>Balance</b></td>
            <td><b>Auto Renew</b></td>
            <td><b>Clicks</b></td>
            <td><b>Impressions</b></td>
            <td><b>Daily Budget</b></td>
            <td><b>Currency</b></td>
        </tr>
        @foreach($advertisements as $advertise)
            <tr class="clickable pageitem">
                <td>{{ $advertise->plan_type }} - {{ \App\Adv_Management::wherePlanId($advertise->id)->lists('title')->implode(',') }} - <small class="text-right"><b><a href="/premium/{{ \App\Adv_Management::wherePlanId($advertise->id)->lists('id')->implode(',') }}/{{ $advertise->id }}/edit">Edit</a></b></small></td>
                <td id="exclude"><input class="active_plan" data-planid="{{ $advertise->id }}" type="checkbox" {{ $advertise->active == 1 ? "checked" : "" }}></td>
                <td>{{ $advertise->plan_price / 100}}</td>
                <td>{{ $advertise->balance / 100 }}</td>
                <td>{{ $advertise->auto_renew == "1" ? "Yes" : "No" }}</td>
                <td>{{ $advertise->total_click }}</td>
                <td>{{ $advertise->total_impression }}</td>
                <td>{{ $advertise->daily_budget }}</td>
                <td>{{ $advertise->currency }}</td>
            </tr>
        @endforeach
    </table>
@section('script')
    <script>
        $("document").ready(function () {
            $(".active_plan").on("click", function () {
                var that = $(this);
                if ($(this).is(":checked")) {
                    $.ajax('/api/stopadv', {
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: $(that).data("planid"),
                            activate: 0
                        },
                        success: function () {
                            swal('Plan Activated', 'You can disable anytime and it will not show advertisement to users', 'success');
                        }
                    });
                } else {
                    $.ajax('/api/stopadv', {
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: $(that).data("planid"),
                            activate: 1
                        },
                        success: function () {
                            swal('Plan Deactivated', 'You can enable again anytime then your advertisement visible to users', 'success');
                        }
                    });
                }
            })
        })
    </script>
@endsection
@endsection