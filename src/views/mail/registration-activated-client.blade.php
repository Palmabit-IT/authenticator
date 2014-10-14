<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Welcome to {{Config::get('authentication::app_name')}}</h2>
<div>
    Goodmorning {{ $body['email'] }}
    <strong>Your username has been activated</strong>
    <br/>
    <strong>'Login with email and password you selected</strong>
    <a href="{{URL::to('/')}}" target="_blank">Go to website</a>
</div>
</body>
</html>