@extends('user')
@section('title')
    Premium Advertisement
@endsection
@section('main-content')
    <div class="col-md-10 col-lg-push-1">
        <h3>Our Plans</h3>

        <p class="auto-renew"><input type="checkbox" id="auto_renew" checked="checked" name="auto_renew" value="1"> Yes i would
            like to auto renew this plan.</p>
        @if(Auth::user()->card_id == "" || empty(Auth::user()->card_id))
            <form action="/plan/checkout" method="post"></form>
            <div class="plan plan1">
                <h5>Basic Package</h5>
                <h4>Dollar</h4>

                <h1>4.44</h1>

                <p>20 Impressions</p>

                <p>20 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    20 Impressions or clicks.
                </p>
                <button class="btn button verify_account" clicks="20" data-amount="444" data-plan="1" plan="basic">Buy
                </button>
            </div>
            <div class="plan plan2 selected-plan">
                <h5>Most Popular</h5>
                <h4>Dollar</h4>

                <h1>9.44</h1>

                <p>50 Impressions</p>

                <p>50 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    50 Impressions or clicks.
                </p>
                <button class="btn button verify_account" clicks="50" data-amount="944" data-plan="2"
                        plan="most_popular">Buy
                </button>
            </div>
            <div class="plan plan3">
                <h5>Best Deal</h5>
                <h4>Dollar</h4>

                <h1>24.99</h1>

                <p>150 Impressions</p>

                <p>150 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    150 Impressions or clicks.
                </p>
                <button class="btn button verify_account" clicks="150" data-amount="2499" data-plan="3"
                        plan="best_deal">Buy
                </button>
            </div>
        @else
            <div class="plan plan1">
                <h5>Basic Package</h5>
                <h4>Dollar</h4>

                <h1>4.44</h1>

                <p>20 Impressions</p>

                <p>20 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    20 Impressions or clicks.
                </p>
                <button class="btn button buy_plan {{ Session::get('plan_type') == 'basic' ? 'yes' : 'no' }}"
                        clicks="20" data-amount="444" data-plan="1" plan="basic">Buy
                </button>
            </div>
            <div class="plan plan2 selected-plan">
                <h5>Most Popular</h5>
                <h4>Dollar</h4>

                <h1>9.44</h1>

                <p>50 Impressions</p>

                <p>50 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    50 Impressions or clicks.
                </p>
                <button class="btn button buy_plan {{ Session::get('plan_type') == 'most_popular' ? 'yes' : 'no' }}"
                        clicks="50" data-amount="944" data-plan="2" plan="most_popular">
                    Buy
                </button>
            </div>
            <div class="plan plan3">
                <h5>Best Deal</h5>
                <h4>Dollar</h4>

                <h1>24.99</h1>

                <p>150 Impressions</p>

                <p>150 Clicks</p>

                <p class="plan-description">
                    Your logo on the Category and Sub Category Page(random)
                    150 Impressions or clicks.
                </p>
                <button class="btn button buy_plan {{ Session::get('plan_type') == 'best_deal' ? 'yes' : 'no' }}"
                        clicks="150" data-amount="2499" data-plan="3" plan="best_deal">Buy
                </button>
            </div>
        @endif
    </div>

    @if(Session::has('customer_id'))
        <script>



            @if(Session::has("customer_id"))

            $(".yes").html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Don\'t Refresh Window');
            var plan = $(".yes").attr('plan');
            $.ajax({
                url: '/plan/create',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    plan_id: $(".yes").attr('data-plan'),
                    customer_id: "{{ Crypt::encrypt(Session::get('customer_id')) }}",
                    plan_type: plan,
                    auto_renew: $("#auto_renew").val()
                },
                complete: function (data) {
                    $("#customButton").html('Payment Done').attr('disabled', 'disabled');
                    $("#auto_renew").attr('disabled', 'disabled');
                    if (data.responseText == "" || data.responseText == "undefined") {
                        alert("There is some error please contact website administrator");
                    } else {
                        return window.location.href = "/premium?plan_id=" + data.responseText;
                    }
                },
                error: function (errorData) {
                    console.log(errorData);
                }
            });

            @endif
        </script>
    @endif

    @if(Auth::user()->card_id == "" || empty(Auth::user()->card_id))
        {{--<h3>Please Verify Your Credit Card.</h3>--}}
        {{--<form action="/plan/checkout" method="POST">--}}
        {{--<script--}}
        {{--src="https://checkout.stripe.com/checkout.js" class="stripe-button"--}}
        {{--data-key="pk_test_Rh54FuBpoz5Bvhu6yznIrhcm"--}}
        {{--data-image="/img/documentation/checkout/marketplace.png"--}}
        {{--data-name="Your Service Connection"--}}
        {{--data-description="No Amount Detect"--}}
        {{--data-amount=0--}}
        {{--data-locale="auto">--}}
        {{--</script>--}}
        {{--{!! Form::token() !!}--}}
        {{--</form>--}}


        {{--<h3><span class="glyphicon glyphicon-ok"></span> Credit Card Verified</h3>--}}
        {{--<section class="col-md-3 plan-1 plans">--}}
        {{--<h3>Basic Package</h3>--}}

        {{--<p>$4.99</p>--}}

        {{--<p>Your logo on the Category and Sub Category Page(random) 20 Impressions or clicks.</p>--}}

        {{--</section>--}}
        {{--<section class="col-md-4 plan-2 plans">--}}
        {{--<h3>Most Popular</h3>--}}

        {{--<p>$9.99</p>--}}

        {{--<p>Your logo on the Category and Sub Category Page(random) 50 Impressions or Clicks.</p>--}}
        {{--</section>--}}
        {{--<section class="col-md-3 plan-3 plans">--}}
        {{--<h3>Best Deal</h3>--}}

        {{--<p>$24.99</p>--}}

        {{--<p>Your logo on the Category and Sub Category Page(random) 150 Impressions or Clicks.</p>--}}
        {{--</section>--}}

        {{--<div class="row">--}}
        {{--<button class="btn btn-success plans add-margin" id="customButton" data-plan="1" plan="basic">Buy--}}
        {{--</button>--}}
        {{--<p>Yes i would like to auto renew this plan <input type="checkbox" id="auto_renew" name="auto_renew"--}}
        {{--value="0"></p>--}}
        {{--</div>--}}

    @endif
@endsection
@section("script")
    <script src="https://checkout.stripe.com/checkout.js"></script>
    @include('partial.js.Plan.plan')
@endsection