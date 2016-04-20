<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Your service connection - @yield('description')">
    <link rel="canonical" href="http://crowdfunds.in/page/@yield('canonical')"/>
    <meta name="ROBOTS" content="INDEX, FOLLOW">
    <meta name="author" content="@yield('author')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="google-site-verification" content="txHaVVh3gwpiIVTX8_-oR2D25IaXuednax3Cc5ZBQOI"/>
    <link href="http://facebook.com/yourserviceconnection" rel="publisher"/>
    {!! Html::style('css/app.css') !!}
    {!! Html::style('css/style.min.css') !!}
    {!! Html::style('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css') !!}
    {!! Html::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css') !!}
    {!! Html::style('/css/dropzone.css') !!}
    <script>function imgError(image) {
            image.onerror = "";
            image.src = "../images/no.gif";
            return true;
        }</script>
</head>
<body class="add-colored-bg">
<div class="load"
     style="position:absolute; display: none; left: 0; top: 0; padding: 3px 20px; z-index: 5; background: #a62222; color: white;">
    Loading...
</div>
<div id="example"></div>
<figure class="jumbotron container">
    <section class="container">
        <header class="navbar bs-docs-nav" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse"
                            data-target=".bs-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="/" class="navbar-brand"><img
                                src="{{ asset('/images/logo.png') }}" alt=""/></a>
                </div>
                <nav class="collapse navbar-collapse bs-navbar-collapse pull-right" role="navigation">
                    <ul class="nav navbar-nav">
                        <li class="dropdown ">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @if(Auth::check())
                                    {{ Auth::user()->name == "" ? Auth::user()->username : Auth::user()->name }}
                                @else
                                    Account
                                @endif
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                @if(Auth::guest())
                                    <li><a href="/auth/login">Login</a></li>
                                    <li><a href="/auth/register">Register</a></li>
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
                            <li>
                                <a href="/message" class="messages">Inbox <span class="badge"></span></a>
                            </li>
                        @endif
                        {{ \App\Http\Controllers\Api::GetPages() }}
                    </ul>
                </nav>
            </div>
        </header>
        @yield('banner');
    </section>
</figure>
<div class="container bodycontainer" id="bodycontainer">
    <div id="search-app">
        <div class="inner-addon left-addon">
            <input type="text" data-provide="typeahead" name="query" value="{{ Input::old('query') }}" id="search"
                   class="form-control search-bar" autocomplete="off" placeholder=""/>
        </div>
    </div>
    <section class="row panel">
        <article class="col-sm-4 no-margin no-padding">
            @if(Auth::guest())
                <i class="fa fa-lock red"><span class="text">Not logged in</span></i>
            @else
                <i class="fa fa-unlock-alt">
                    <span class="text">You are logged as <b>
                            <a href="/profile">{{ Auth::user()->name == "" ? Auth::user()->username : Auth::user()->name }}</a></b>
                        </span>
                </i>
            @endif
        </article>
        @if(Auth::guest())
            <article class="col-md-8 hidden-sm hidden-xs text-right no-padding">
                <i class="fa fa-external-link-square no-margin"><span class="text"><a
                                href="/auth/login">Login</a></span></i>
            </article>
        @else
            <article class="col-md-8 hidden-sm hidden-xs text-right no-padding">
                <i class="fa fa-external-link-square"><span class="text"><a
                                href="/auth/logout">Logout</a></span></i>
                <i class="fa fa-key"><span class="text"><a
                                href="/password/change">Change Password</a></span></i>

                <i class="fa fa-envelope"><a class="low-opacity" href="/message"><span class="text"
                                                                                       id="newmessages">Messages</span></a></i>
                <i class="fa fa-bell no-margin low-opacity"><span class="text notification">Notifcations</span></i>
            </article>
        @endif
    </section>
    @yield('content')
</div>
<footer class="panel-footer footer container">
    <div class="container footer-links">
        <figure class="row">
            <section class="col-md-3">
                <h4 class="title">SITE LINKS</h4>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="https://www.yourserviceconnection.com/user/about-us">About us</a></li>
                    <li><a href="https://www.yourserviceconnection.com/user/contact-us">Contact us</a></li>
                </ul>
            </section>
            <section class="col-md-3">
                <h4 class="title">DASHBOARD</h4>
                <ul>
                    <li><a href="https://www.yourserviceconnection.com/user/login">Login</a></li>
                    <li><a href="https://www.yourserviceconnection.com/user/registration">Register</a></li>
                </ul>
            </section>
            <section class="col-md-3">
                <h4 class="title">BLOG</h4>
                <ul>
                    <li><a href="http://blog.yourserviceconnection.com">Home</a></li>
                    <li><a href="http://blog.yourserviceconnection.com/single/25">Recent Posts</a></li>
                    <li><a href="http://blog.yourserviceconnection.com/onlyme">Login</a></li>
                </ul>
            </section>
            <section class="col-md-3">
                <h4 class="footer-logo text-right"><img src="{{ asset('/images/logo.png') }}" width="200" alt=""/>
                </h4>
            </section>
        </figure>
        <figure class="row">
            <section class="col-md-5">
                <h6 class="copyright">&copy; 2015 All Right Reserved - Your Service Connection</h6>
            </section>
        </figure>
    </div>
</footer>

<div class="loading">
    <h1>Loading Please Wait...</h1>
</div>

{{-- Local --}}
{{--<script src="lib/jquery.js"></script>--}}
{{--<script src="lib/bootstrap.min.js"></script>--}}
{{--<script src="lib/jquery.infinitescroll.js"></script>--}}
{{--<script src="lib/vue.js"></script>--}}

<script src="https://code.jquery.com/jquery-2.2.1.js" integrity="sha256-eNcUzO3jsv0XlJLveFEkbB8bA7/CroNpNVk3XpmnwHc="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/tether/1.1.1/js/tether.min.js') !!}
{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js') !!}
{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js') !!}
{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.14/vue.min.js') !!}
{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.6.1/vue-resource.min.js') !!}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>
{!! Html::script('/lib/app.min.js') !!}
<script src="https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<?php include(public_path('/lib/premium.phtml')) ?>
@include('partial.notify')
@yield('script')
@if(Auth::check())

    <script>

        var source = new EventSource("/messages");
        source.onmessage = function (event) {
            if (event.data != 0) {
                $("#newmessages").html(" " + event.data + " New Message's").parent().removeClass('low-opacity').addClass('red');
            }
        };
        var notification = new EventSource("/notification");
        notification.onmessage = function (event) {
            if (event.data != 0) {
                $(".notification").html(" " + event.data + " New Notification's").parent().removeClass('low-opacity').addClass('red');
            }
        };

    </script>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-53421234-1', 'auto');
        ga('send', 'pageview');

    </script>
@endif
</body>
</html>
