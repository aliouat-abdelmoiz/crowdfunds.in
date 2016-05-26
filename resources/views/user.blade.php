@extends('app')
@section('title')
    @if(Auth::check())
        {{ Auth::user()->name }} - Your Service Connection
    @else
        Your Service Connection
    @endif
@endsection
@section('content')
    @if(Auth::check())
        <div class="col-md-2 profile text-center">
            <h4 class="title">User Profile</h4>

            @if(Auth::user()->hasRole('Provider'))
                <img onerror="imgError(this)" src="/uploads/users/logos/{{ Auth::user()->provider->logo == "" ? Auth::user()->avatar : Auth::user()->provider->logo }}"
                     alt="">
            @else
                <img onerror="imgError(this)" src="/uploads/users/logos/{{  Auth::user()->avatar }}"
                     alt="">
            @endif

            <p class="user_name"><i data-toggle="tooltip" data-placement="top"
                                    title="{{ Session::get('activate') == '0' ? 'Account not activated' : 'Account Verified' }}"
                                    class="fa {{ Session::get('activate') == "0" ? "fa-warning red verify" : "fa-check green" }}"></i></a> {{ Auth::user()->name }}
            </p>
            <p class="user_email">
                <small>{{ Auth::user()->email }}</small>
            </p>
            <p class="zip_code">Zip - {{ Auth::user()->userinfo->zip_code }}</p>

            <p class="city_state">{{ Auth::user()->userinfo->city }}, {{ Auth::user()->userinfo->state }}</p>
            @if(Auth::user()->userinfo->phone != "" || !empty(Auth::user()->userinfo->phone) || Auth::user()->userinfo->phone != null)
                <p class="phone">Phone : {{ Auth::user()->userinfo->phone }}</p>
            @endif
            <p><a class="profile_link" href="/user/{{ Auth::id() }}/edit">Edit Profile</a></p>

            @if(Auth::user()->hasRole('Provider'))
                <p><a class="profile_link" href="/profile/{{Auth::user()->provider->id}}">Your
                        Provider Profile</a></p>
                <p><a class="profile_link" href="/projects">Projects</a></p>
                <p><b><a href="/plan/show" class="profile_link">Premium Plans</a></b></p>
            @endif

            <p>&nbsp;</p>

            <p class="text-center">
                <input type="button" onclick="javascript: window.location.href = '/jobs'" class="btn btn-red-job btn-sm"
                       value="Create new Job">
                @if(!Auth::user()->hasRole('Provider'))
                    <input type="button" onclick="javascript: window.location.href = '/profile/create'"
                           class="btn btn-blue-job btn-sm add-margin"
                           value="Become a Provider">
                @endif

            </p>
        </div>
    @endif
    @yield('main-content')
    @if(Auth::check())
        <div class="modal fade verify_account" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Verify Email Account</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            <input type="text" class="form-control textbox" placeholder="Enter Email Address"
                                   value="{{ Auth::user()->email }}">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info btn-sm send_request">Verify</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif
@endsection
@section('script')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            if ($(".user_name i").hasClass('verify')) {
                $(".user_name i").click(function () {
                    $(".verify_account").modal();
                });
                $(".send_request").click(function () {
                    $(this).text("Loading...");
                    window.location.href = "/auth/verify";
                });
            }
        });
    </script>
@endsection

