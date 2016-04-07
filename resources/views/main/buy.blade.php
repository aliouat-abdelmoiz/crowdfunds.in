@extends('app')
@section('content')
    {!! Form::open(["class" => "form", 'id' => 'billing-form']) !!}
    <div id="payment">
        <div class="alert-danger panel card-error" style="display: none;"></div>
        <div class="form-group">
            <label for="card">Card Number :</label>
            <input type="text" class="form-control" data-stripe="number">
        </div>
        <div class="form-group">
            <label for="card">CVC :</label>
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
        <div class="form-group">
            {!! Form::submit('Checkout', ['class' => 'btn btn-blue-job']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@section('script')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.13/vue.min.js"></script>
    <script src="/lib/billing.js"></script>
@endsection