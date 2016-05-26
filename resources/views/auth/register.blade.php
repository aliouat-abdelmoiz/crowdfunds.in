@extends('app')
@section('content')
    <div class="panel-body">
        @if (count($errors) > 0)
            <div class="alert">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="add-margin">Register New Account</h1>

        <form role="form" class="user_form" method="POST" action="{{ url('/auth/register') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="text" name="fullname" value="{{ Input::old('fullname') }}" id="name"
                   placeholder="Fullname"/>
            <input type="text" name="username" id="username" value="{{ Input::old('username') }}"
                   placeholder="Username"/>
            @if(Request::get("email") != null)
                <label for="email" class="green">Your Email is Valid</label>
                <input type="text" name="email" id="email" value="{{ Request::get('email') }}"
                       placeholder="Enter Email Address"/>
                <input type="hidden" value="true" name="validate">
            @else
                <input type="text" name="email" id="email" value="{{ Input::old('email') }}"
                       placeholder="Enter Email Address"/>
                <input type="hidden" value="false" name="validate">
            @endif
            <input type="text" name="email_confirmation" value="{{ Input::old('email_confirmation') }}"
                   id="email_confirmation" placeholder="Re-type Email Address"/>
            <input type="password" name="password" id="password"
                   placeholder="Password - Should be 8 Character Long"/>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   placeholder="Re-type Password"/>
            <input type="text" name="zip" id="postal" v-on:keyup="sendData" placeholder="Zip Code"/>
            <div class="text-error"></div>
            <span class="iaddress"></span>

            <p class="choiseCities">
                We Found more then one Location with this zip select your near by Location.
            <hr/>
            <input list="cities" id="datacities" placeholder="Select Other Nearby Location"/>
            <datalist id="cities"></datalist>
            </p>
            <div class="btn-group add-margin">
                <input type="submit" value="Register" class="btn btn-success"/>
                <input type="reset" class="btn btn-danger"/>
            </div>

            <input type="hidden" value="" id="current_place" name="current_place"/>
            <input type="hidden" value="" id="city" name="city"/>
            <input type="hidden" value="" id="state" name="state"/>
            <input type="hidden" value="" id="zip" name="zip"/>
            <input type="hidden" value="USA" id="country" name="country"/>
            <input type="hidden" value="" id="longitude" name="longitude"/>
            <input type="hidden" value="" id="latitude" name="latitude"/>

        </form>

    </div>


@endsection