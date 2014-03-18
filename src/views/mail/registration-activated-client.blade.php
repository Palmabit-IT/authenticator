<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{L::t('Welcome to')}} {{Config::get('authentication::app_name')}}</h2>
<div>
    {{L::t('Goodmorning')}} {{ $body['email'] }}
    <strong>{{L::t('Your username has been activated')}}.</strong>
    <br/>
    <strong>{{L::t('Login with email and password you selected')}}.</strong>
    <a href="{{URL::to('/')}}" target="_blank">{{L::t('Go shopping')}}!</a>
</div>
</body>
</html>