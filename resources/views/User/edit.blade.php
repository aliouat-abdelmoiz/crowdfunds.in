@extends('user')
@if(Auth::check())
@section('title')
    {{ Auth::user()->name }} - Your Service Connection
@endsection
@endif
@section('main-content')
    <div class="col-md-10">
        <h3>Edit Profile</h3>
        {!! Form::model($user,['method' => 'patch', 'route' => ['user.update', $user->id], 'class' => 'form form-group', 'files' => 'true']) !!}
        <label for="name"><b>Fullname</b></label>
        {!! Form::input('text', 'name', $user->user->name, ['class' => 'form-control']) !!}
        <label for="zip_code"><b>Zip Code</b></label>
        {!! Form::input('text', 'zip_code', $user->zip_code, ['class' => 'form-control', 'id' => 'postal', 'v-on:keyup' => 'sendDataManual']) !!}
        <label for="country"><b>Country</b></label>
        {!! Form::input('text', 'country', $user->country, ['class' => 'form-control', 'id' => 'country']) !!}
        <label for="city"><b>City</b></label>
        {!! Form::input('text', 'city', $user->city, ['class' => 'form-control', 'id' => 'city']) !!}
        <label for="state"><b>State</b></label>
        {!! Form::input('text', 'state', $user->state, ['class' => 'form-control', 'id' => 'state']) !!}
        <label for="phone"><b>Phone</b></label>
        {!! Form::input('text', 'phone', $user->phone, ['class' => 'form-control', 'id' => 'phone']) !!}
        <label for="address"><b>Address</b></label>
        {!! Form::input('text', 'address', $user->address, ['class' => 'form-control']) !!}
        <label for="address2"><b>Address 2</b></label>
        {!! Form::input('text', 'address2', $user->address2, ['class' => 'form-control']) !!}
        <label for="image">Upload Profile Image :</label>
        {!! Form::file('avatar') !!}
        {!! Form::hidden('latitude', $user->latitude, ['id' => 'latitude']) !!}
        {!! Form::hidden('longitude', $user->longitude, ['id' => 'longitude']) !!}
        {!! Form::submit('Save', ['class' => 'btn btn-blue-job btn-sm']) !!}
        {!! Form::reset('Clear', ['class' => 'btn btn-red-job btn-sm']) !!}
        {!! Form::close() !!}
    </div>
@stop

@section('scripts')
    <script>
        new Vue({
            el: "#app",
            ready: function() {
                alert("asd");
            }
        });
    </script>
@stop