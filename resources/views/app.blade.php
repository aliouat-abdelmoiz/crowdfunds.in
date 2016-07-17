<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta http-equiv="Cache-control" content="public">
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="30"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
    <meta content="Your service connection - @yield('description')" name="description">
    <link href="https://www.yourserviceconnection.com/page/@yield('canonical')" rel="canonical"/>
    <meta content="INDEX, FOLLOW" name="ROBOTS">
    <meta content="@yield('author')" name="author">
    <meta content="@yield('keywords')" name="keywords">
    <meta content="txHaVVh3gwpiIVTX8_-oR2D25IaXuednax3Cc5ZBQOI" name="google-site-verification"/>
    <link href="https://facebook.com/yourserviceconnection" rel="publisher"/>
    <!-- bower:css -->

    <!-- endbower -->
    <noscript id="deferred-styles">
        {!! Html::style('css/app.css') !!}
        {!! Html::style('css/style.min.css') !!}
    </noscript>
    <script>
        var loadDeferredStyles = function() {
            var addStylesNode = document.getElementById("deferred-styles");
            var replacement = document.createElement("div");
            replacement.innerHTML = addStylesNode.textContent;
            document.body.appendChild(replacement);
            addStylesNode.parentElement.removeChild(addStylesNode);
        };
        var raf = requestAnimationFrame || mozRequestAnimationFrame ||
                webkitRequestAnimationFrame || msRequestAnimationFrame;
        if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
        else window.addEventListener('load', loadDeferredStyles);
    </script>
</head>
<body class="add-colored-bg">
<div class="load"
     style="position:absolute; display: none; left: 0; top: 0; padding: 3px 20px; z-index: 5; background: #a62222; color: white;">
    Loading...
</div>
<div id="example"></div>
<figure class="jumbotron container-fixed">
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
        <article class="col-lg-4 no-margin no-padding">
            @if(Auth::guest())
                <i class="fa fa-lock red"><span class="text">Not logged in</span></i>
            @else
                <i class="fa fa-unlock-alt">
                    <span class="text">You are logged as <b>
                            <a href="/profile">{{ Auth::user()->name == "" ? Auth::user()->username : Auth::user()->name }}</a></b>
                        </span>
                    @if(Auth::check())
                        @if(Auth::user()->subscription == null)
                            <button class="btn btn-sm btn-success" id="subscribe" v-on:click="subscribe">Subscribe
                            </button>
                        @else
                            @if(Auth::user()->subscription->subscribed == 1)
                                <button class="btn btn-sm btn-success" id="unsubscribe" v-on:click="unsubscribe">
                                    Unsubscribe
                                </button>
                            @else
                                <button class="btn btn-sm btn-success" id="subscribe" v-on:click="subscribe">Subscribe
                                </button>
                            @endif
                        @endif
                    @endif
                </i>
            @endif
        </article>
        @if(Auth::guest())
            <article class="col-md-8 text-right no-padding">
                <i class="fa fa-external-link-square no-margin"><span class="text"><a
                                href="/auth/login">Login</a></span></i>
            </article>
        @else
            <article class="col-md-8 text-right no-padding">
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
<footer class="panel-footer footer container-fixed">
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

<!-- bower:js -->

<!-- endbower -->
{!! Html::script('/lib/app.min.js', ['async']) !!}
<?php include public_path('/lib/premium.phtml')?>
@include('partial.notify')
@yield('script')
@if(Auth::check())
    <script async>
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
@endif
<?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false): ?>
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
<?php endif; ?>


</body>
</html>
