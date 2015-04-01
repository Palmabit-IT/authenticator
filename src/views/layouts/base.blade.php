{{-- Layout base admin panel --}}
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    {{HTML::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css')}}
    {{ HTML::style('packages/palmabit/authenticator/css/sticky-footer.css') }}
    {{ HTML::style('packages/palmabit/authenticator/css/style.css') }}

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
            &copy; {{$copy_year}} • {{$copy_name}} •
            <a href="http://{{$copy_website_url}}">{{$copy_website_url}}</a>
        </p>
    </div>
</div>

{{-- Start footer scripts --}}
@yield('before_footer_scripts')

{{ HTML::script('http://code.jquery.com/jquery-1.11.0.js') }}
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js') }}

@yield('footer_scripts')
{{-- End footer scripts --}}

</body>
</html>