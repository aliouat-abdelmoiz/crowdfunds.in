@extends('app')
@section('content')
    <div class="container">
        <form action="/profile/edituser" method="post">
            <figure class="col-md-8">
                <h1 class="add-margin">Edit User</h1>

                <p>You are from <span class="iaddress"></span></p><br>
                <figure class="form-group">
                    {!! Form::label('zipLabel', 'Zip Code: ') !!}
                    {!! Form::input('text', 'zipcode_field', $user[0]->userinfo->zip_code, ['class' => 'form-control zipcode',
                    'id' => 'postal']) !!}
                </figure>
                <figure class="form-group">
                    {!! Form::label('countryLabel', 'Country: ') !!}
                    {!! Form::input('text', 'country', $user[0]->userinfo->country, ['class' => 'form-control country',
                    'id' => 'country']) !!}
                </figure>
                <figure class="form-group">
                    {!! Form::label('stateLabel', 'State: ') !!}
                    {!! Form::input('text', 'state', $user[0]->userinfo->state, ['class' => 'form-control state', 'id'
                    => 'state']) !!}
                </figure>
                <figure class="form-group">
                    {!! Form::label('cityLabel', 'City: ') !!}
                    {!! Form::input('text', 'city', $user[0]->userinfo->city, ['class' => 'form-control city', 'id'
                    => 'city']) !!}
                </figure>
                <figure class="form-group">
                    {!! Form::label('addressLabel', 'Address: ') !!}
                    {!! Form::input('text', 'address', $user[0]->userinfo->address, ['class' => 'form-control address',
                    'id' => 'address']) !!}
                </figure>
                <figure class="form-group">
                    {!! Form::label('addressLabel2', 'Address 2: ') !!}
                    {!! Form::input('text', 'address2', $user[0]->userinfo->address2, ['class' => 'form-control
                    address2', 'id' => 'address2']) !!}
                </figure>
                <figure class="form-group">
                    {!! Form::label('phoneLabel', 'Phone: ') !!}
                    {!! Form::input('text', 'phone', $user[0]->userinfo->phone, ['class' => 'form-control phone', 'id'
                    => 'phone']) !!}
                </figure>
                <input type="hidden" name="current_place" value="" id="current_place">
                <input type="hidden" name="zipcode" value="" id="zip">
                <input type="hidden" name="latitude" value="" id="latitude">
                <input type="hidden" name="longitude" value="" id="longitude">
                {!! Form::token() !!}
                <p>
                    <input type="submit" class="btn btn-red" value="Save User Profile">
                </p>
            </figure>
        </form>
    </div>
    <script src="/lib/actions.js"></script>
@endsection