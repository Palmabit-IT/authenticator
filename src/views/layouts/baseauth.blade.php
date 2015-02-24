{{-- Layout base used for authentication --}}
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">


    {{ HTML::style('packages/palmabit/authenticator/css/bootstrap.min.css') }}
    {{ HTML::style('packages/palmabit/authenticator/css/style.css') }}
    {{ HTML::style('packages/palmabit/authenticator/css/signin.css') }}

    @yield('head_css')
    {{-- End head css --}}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
{{-- content --}}
<div class="container">
    @yield('container')
</div>

{{-- Start footer scripts --}}
{{ HTML::script('packages/palmabit/authenticator/js/jquery-1.10.2.min.js') }}
{{ HTML::script('packages/palmabit/authenticator/js/bootstrap.min.js') }}
</body>
</html>