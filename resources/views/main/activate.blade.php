@extends('app')
@section('content')
    <div class="container">
        <section class="add-margin">
            <h1>Please activate your account.</h1>
            <p>If you haven't received activation email please click below link.</p>
            <hr/>
            <a href="/auth/verify" class="btn btn-default"><i class="icon-bar icon-next"></i>Resend Activation Email</a>
            <p class="alert-info add-margin set-padding add-radius">
                <small>You have 2 days left to activate account.</small>                    
            </p>
        </section>
    </div>
@endsection