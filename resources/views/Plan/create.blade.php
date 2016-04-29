@extends('user')
@section('main-content')
    <div class="col-md-10 col-lg-push-1" id="plans">
        <h3>Our Plans</h3>

        <form action="{{ URL::route('premium.index') }}" method="get">
            <p class="auto-renew"><input type="checkbox" checked id="auto_renew" name="auto_renew" value="1"> Yes i would
                like to auto renew this plan.</p>
            <div class="plan plan1">
                <h5>Basic Package</h5>
                <h4>Dollar</h4>

                <h1>$4.44</h1>

                <p>20 Impressions</p>

                <p>20 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    20 Impressions or clicks.
                </p>
                {!! Form::hidden('amount', '499') !!}
                {!! Form::hidden('clicks', '20') !!}
                {!! Form::hidden('plan', '1') !!}
                {!! Form::hidden('plan_name', 'basic') !!}
                <button class="btn button verify_account">Buy</button>
            </div>
        </form>
        <form action="{{ URL::route('premium.index') }}" method="get">
            <div class="plan plan2 selected-plan">
                <h5>Most Popular</h5>
                <h4>Dollar</h4>

                <h1>$9.44</h1>

                <p>50 Impressions</p>

                <p>50 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    50 Impressions or clicks.
                </p>
                {!! Form::hidden('amount', '944') !!}
                {!! Form::hidden('clicks', '50') !!}
                {!! Form::hidden('plan', '2') !!}
                {!! Form::hidden('plan_name', 'most_popular') !!}
                <button class="btn button verify_account">Buy</button>
            </div>
        </form>
        <form action="{{ URL::route('premium.index') }}" method="get">
            <div class="plan plan3">
                <h5>Best Deal</h5>
                <h4>Dollar</h4>

                <h1>$24.99</h1>

                <p>150 Impressions</p>

                <p>150 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    150 Impressions or clicks.
                </p>
                {!! Form::hidden('amount', '2499') !!}
                {!! Form::hidden('clicks', '150') !!}
                {!! Form::hidden('plan', '3') !!}
                {!! Form::hidden('plan_name', 'best_deal') !!}
                <button class="btn button verify_account">Buy</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="/lib/advertise.js"></script>
@endsection
