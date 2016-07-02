<!doctype html>
<html lang="en">
    <head>
            <meta charset="utf-8">
            <title>@yield('title')</title>
            <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
            <meta content="Your service connection - @yield('description')" name="description">
            <link href="https://www.yourserviceconnection.com/page/@yield('canonical')" rel="canonical"/>
            <meta content="INDEX, FOLLOW" name="ROBOTS">
            <meta content="@yield('author')" name="author">
            <meta content="@yield('keywords')" name="keywords">
            <meta content="txHaVVh3gwpiIVTX8_-oR2D25IaXuednax3Cc5ZBQOI" name="google-site-verification"/>
            <link href="https://facebook.com/yourserviceconnection" rel="publisher"/>
            <!-- bower:css -->
            <link rel="stylesheet" href="/bower_components/animate.css/animate.css" />
            <link rel="stylesheet" href="/bower_components/sweetalert/dist/sweetalert.css" />
            <link rel="stylesheet" href="/bower_components/dropzone/dist/min/dropzone.min.css" />
            <!-- endbower -->
            {!! Html::style('css/app.css') !!}
            {!! Html::style('css/style.min.css') !!}
    </head>
    <body class="add-colored-bg">
    <div id="app">
        <div class="load">Loading...</div>
        <div id="example"></div>
        <figure class="jumbotron container">
            <section class="container">
                <header class="navbar bs-docs-nav" role="banner">
                    <div class="container">
                        <div class="navbar-header">
                            <button class="navbar-toggle" data-target=".bs-navbar-collapse" data-toggle="collapse" type="button">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="/">
                                <img alt="Your Service Connection" src="{{ asset('images/logo.png') }}"/>
                            </a>
                        </div>
                        <nav class="collapse navbar-collapse bs-navbar-collapse pull-right" role="navigation">
                            <ul class="nav navbar-nav">
                                <li class="dropdown ">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        @if(Auth::check())
                                            {{ Auth::user()->name == "" ? Auth::user()->username : Auth::user()->name }}
                                        @else
                                            Account
                                        @endif
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @if(Auth::guest())
                                            <li><a href="/auth/login">Login</a></li>
                                            <li><a href="/auth/register">Register</a>
                                        </li>
                                        @else
                                            <li><a href="/user">Profile</a></li>
                                            @if(Auth::user()->hasRole('Provider'))
                                            <li><a href="/plan/show">Premium</a></li>
                                            <li><a href="/advertise/admin">My Active Plans</a></li>
                                            @endif
                                        <li><a href="/auth/logout">Logout</a></li>
                                        <li><a href="/account/delete">Delete Account</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @if(Auth::check())
                                    <li><a class="messages" href="/message">Inbox<span class="badge"></span></a></li>
                                @endif
                                {{ \App\Http\Controllers\Api::GetPages() }}
                            </ul>
                        </nav>
                    </div>
                </header>
                @yield('banner');
                <script async="" src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- yourserviceconnection -->
                <ins class="adsbygoogle" data-ad-client="ca-pub-8637984357942481" data-ad-format="auto" data-ad-slot="6327224655" style="display:block"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </section>
        </figure>
        <div class="container bodycontainer" id="bodycontainer">
            <div class="row add-margin">
                <div class="col-md-3">
                    <div class="g-plusone" data-annotation="inline" data-size="tall" data-width="300"></div>
                </div>
                <div class="col-md-3">
                    <div class="fb-like" data-action="like" data-href="https://facebook.com/yourserviceconnection.com" data-layout="button_count" data-share="false" data-show-faces="false"></div>
                    </div>
                    <div class="col-md-3"><a class="twitter-follow-button" data-show-count="true" href="https://twitter.com/ursvcconnection">
                            Follow @ursvcconnection
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a class="ig-b- ig-b-v-24" href="https://www.instagram.com/yourserviceconnection/?ref=badge">
                            <img alt="Instagram" src="//badges.instagram.com/static/images/ig-badge-view-24.png"/>
                        </a>
                    </div>
                    <ins class="adsbygoogle" data-ad-client="ca-pub-8637984357942481" data-ad-format="auto" data-ad-slot="6327224655" style="display:block">
                    </ins>
                </div>
                <div id="search-app">
                    <div class="inner-addon left-addon">
                        <input autocomplete="off" class="form-control search-bar" data-provide="typeahead" id="search" name="query" placeholder="" type="text" value="{{ Input::old('query') }}"/>
                    </div>
                </div>
                <section class="row panel">
                    <article class="col-sm-4 no-margin no-padding">
                        @if(Auth::guest())
                            <i class="fa fa-lock red"><span class="text">Not logged in</span></i>
                        @else
                        <i class="fa fa-unlock-alt">
                            <span class="text">You are logged as
                                <b>
                                    <a href="/profile">
                                        {{ Auth::user()->name == "" ? Auth::user()->username : Auth::user()->name }}
                                    </a>
                                </b>
                            </span>
                        </i>
                        @endif
                    </article>
                    @if(Auth::guest())
                    <article class="col-md-8 hidden-sm hidden-xs text-right no-padding">
                        <i class="fa fa-external-link-square no-margin">
                            <span class="text">
                                <a href="/auth/login">
                                    Login
                                </a>
                            </span>
                        </i>
                    </article>
                    @else
                    <article class="col-md-8 hidden-sm hidden-xs text-right no-padding">
                        <i class="fa fa-external-link-square">
                            <span class="text">
                                <a href="/auth/logout">
                                    Logout
                                </a>
                            </span>
                        </i>
                        <i class="fa fa-key">
                            <span class="text">
                                <a href="/password/change">
                                    Change Password
                                </a>
                            </span>
                        </i>
                        <i class="fa fa-envelope">
                            <a class="low-opacity" href="/message">
                                <span class="text" id="newmessages">
                                    Messages
                                </span>
                            </a>
                        </i>
                        <i class="fa fa-bell no-margin low-opacity">
                            <span class="text notification">
                                Notifcations
                            </span>
                        </i>
                    </article>
                    @endif
                </section>
                @yield('content')
            </br>
        </div>
        <footer class="panel-footer footer container">
            <div class="container footer-links">
                <figure class="row">
                    <section class="col-md-3">
                        <h4 class="title">
                            SITE LINKS
                        </h4>
                        <ul>
                            <li>
                                <a href="/">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="https://www.yourserviceconnection.com/about-us">
                                    About us
                                </a>
                            </li>
                            <li>
                                <a href="https://www.yourserviceconnection.com/contact-us">
                                    Contact us
                                </a>
                            </li>
                        </ul>
                    </section>
                    <section class="col-md-3">
                        <h4 class="title">
                            DASHBOARD
                        </h4>
                        <ul>
                            <li>
                                <a href="https://www.yourserviceconnection.com/auth/login">
                                    Login
                                </a>
                            </li>
                            <li>
                                <a href="https://www.yourserviceconnection.com/auth/register">
                                    Register
                                </a>
                            </li>
                        </ul>
                    </section>
                    <section class="col-md-3">
                        <h4 class="title">
                            BLOG
                        </h4>
                        <ul>
                            <li>
                                <a href="https://blog.yourserviceconnection.com">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="https://blog.yourserviceconnection.com/single/25">
                                    Recent Posts
                                </a>
                            </li>
                            <li>
                                <a href="https://blog.yourserviceconnection.com/onlyme">
                                    Login
                                </a>
                            </li>
                        </ul>
                    </section>
                    <section class="col-md-3">
                        <h4 class="footer-logo text-right">
                            <img alt="Your Service Connection" src="{{ asset('/images/logo.png') }}" width="200"/>
                        </h4>
                    </section>
                </figure>
                <figure class="row">
                    <section class="col-md-5">
                        <h6 class="copyright">
                            © 2015 All Right Reserved - Your Service Connection
                        </h6>
                    </section>
                </figure>
            </div>
        </footer>
        <div class="loading">
            <h1>
                Loading Please Wait...
            </h1>
        </div>
        <!-- bower:js -->
        <script src="/bower_components/jquery/dist/jquery.js"></script>
        <script src="/bower_components/bootstrap/dist/js/bootstrap.js"></script>
        <script src="/bower_components/vue/dist/vue.js"></script>
        <script src="/bower_components/jquery.maskedinput/dist/jquery.maskedinput.js"></script>
        <script src="/bower_components/sweetalert/dist/sweetalert.min.js"></script>
        <script src="/bower_components/jquery-infinite-scroll/jquery.infinitescroll.js"></script>
        <script src="/bower_components/vue-resource/dist/vue-resource.js"></script>
        <script src="/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <!-- endbower -->
        <?php include public_path('/lib/premium.phtml')?>
        @include('partial.notify')
        @yield('script')
        @if(Auth::check())
            <script>
                var source = new EventSource("/messages");
                source.onmessage = function(event) {
                    if (event.data != 0) {
                        $("#newmessages").html(" " + event.data + " New Message's").parent().removeClass('low-opacity').addClass('red');
                    }
                };
                var notification = new EventSource("/notification");
                notification.onmessage = function(event) {
                    if(event.data != 0) {
                        $(".notification").html(" " + event.data + " New Notification's").parent().removeClass('low-opacity').addClass('red');
                    }
                };
            </script>
        @endif
        </div>
        {!! Html::script('/lib/app.min.js') !!}
    </body>
</html>
