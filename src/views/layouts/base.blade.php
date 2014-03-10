{{-- Layout base admin panel --}}
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    {{ HTML::style('packages/palmabit/authentication/css/bootstrap.min.css') }}
    {{ HTML::style('packages/palmabit/authentication/css/style.css') }}
    {{ HTML::style('packages/palmabit/authentication/css/sticky-footer.css') }}

    @yield('head_css')
    {{-- End head css --}}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

    <body>
        {{-- navbar --}}
        @include('authentication::layouts.navbar')

        {{-- content --}}
        <div class="container container-body">
            @yield('container')
        </div>


        <div id="footer">
          <hr>
          <div class="container">
            <p class="phelp">
                <small>Report a problem <a href="mailto:hello@palmabit.com">hello@palmabit.com</a><br>
                &copy; 2014 • Palmabit • <a href="http://www.palmabit.com">palmabit.com</a></small>
            </p>
          </div>
        </div>

        {{-- Start footer scripts --}}
        @yield('before_footer_scripts')

        {{ HTML::script('packages/palmabit/authentication/js/jquery-1.10.2.min.js') }}
        {{ HTML::script('packages/palmabit/authentication/js/bootstrap.min.js') }}

        @yield('footer_scripts')
        {{-- End footer scripts --}}

    </body>
</html>