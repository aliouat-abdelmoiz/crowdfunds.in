@extends('user')
@section('title')
    Place Advertise on {{ date('F l Y') }}
@endsection
@section('main-content')
    <div class="col-md-10">
        @if($errors->all())
            <section class="alert-danger panel" style="border-radius: 5px; padding: 10px; margin-top: 10px">
                @foreach($errors->all() as $error)
                    <p style="font-size: 15px;">{{ $error }}</p>
                @endforeach
            </section>
        @endif
        <h1 class="add-margin">Advertise</h1>
        {!! Form::open(['route' => 'premium.store', 'files' => true, 'method' => 'post', 'name' => 'advertise', 'id' => 'billing-form']) !!}
        {!! Form::hidden('plan', Input::get('plan')) !!}
        {!! Form::hidden('plan_name', Input::get('plan_name')) !!}
        {!! Form::hidden('clicks', Input::get('clicks')) !!}
        {!! Form::hidden('amount', Input::get('amount')) !!}
        {!! Form::hidden('auto_renew', Input::get('auto_renew')) !!}

        {!! Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control', 'required']) !!}
        <br>
        {!! Form::textarea('description', null, ['placeholder' => 'Description', 'class' => 'form-control', 'required']) !!}
        <br>
        @if(Auth::user()->provider->logo != null || Auth::user()->provider->logo != "")
            <h3>Current Logo</h3>
            <img src="/uploads/users/logos/{{ Auth::user()->provider->logo == "" ? Auth::user()->avatar : Auth::user()->provider->logo }}"
                 alt="">
            <h4>Select Other Image</h4>
            {!! Form::file('images[]', ['multiple' => true, 'class' => 'form-control']) !!}
        @else
            <h4>Select Other Image</h4>
            {!! Form::file('images[]', ['multiple' => true, 'class' => 'form-control']) !!}
        @endif
        <select name="categories" id="category" class="form-control add-margin" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="subcategories[]" id="subcategory" class="form-control add-margin" multiple required></select>
        <hr>
        <div id="payment" style="padding: 20px; background: #FFFBF0; margin-bottom: 30px;">
            <h5 style="color: black;">We need your card information to proceed with payment. Don't worry it's safe using
                <a href="http://stripe.com"><img src="/images/black.png" width="50" alt=""></a> Gateway.</h5>
            <hr>
            <div class="alert-danger panel card-error" style="display: none;"></div>
            <div class="form-group">
                <label for="card">Card Number :</label>
                <input type="text" class="form-control card-number" data-stripe="number">
            </div>
            <div class="form-group">
                <label for="card">CVV :</label>
                <input type="text" class="form-control" data-stripe="cvc">
            </div>
            <div class="form-group">
                <label for="card">Expire Month :</label>
                {!! Form::selectMonth(null, null, ['data-stripe' => 'exp-month', 'class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label for="card">Expire Year :</label>
                {!! Form::selectYear(null, date('Y'), date('Y') + 10, null, ['data-stripe' => 'exp-year', 'class' => 'form-control']) !!}
            </div>
            <div class="form-group text-right">
                {!! Form::submit('Checkout', ['class' => 'btn btn-blue-job']) !!}
            </div>
        </div>
        {{--<p><input id="submit-btn" type="submit" class="btn btn-success btn-lg pull-left" value="Proceed to Payment">&nbsp;&nbsp;&nbsp;&nbsp;--}}
        {{--<span class="subscribe-process process" style="display:none;">Processing&hellip;</span>--}}
        {{--<small class="alert-danger text-danger"></small>--}}
        {!! Form::close() !!}
    </div>

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="/lib/billing.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.css">
    <script>
        $("document").ready(function () {
            $(document.body).on("change click", "#category", function () {
                $.ajax({
                    url: '/api/subcategories',
                    type: 'JSON',
                    method: 'GET',
                    data: {category_id: $(this).val()},
                    success: function (result) {
                        var x = JSON.parse(result);
                        $("#subcategory").html("");
                        $.each(x, function (key, value) {
                            $("#subcategory").append("<option selected='selected' value=" + value.id + ">" + value.name + "</option>");
                            $('#subcategory').multipleSelect();
                        });
                    }
                });
            });
        })
    </script>
@endsection
@endsection