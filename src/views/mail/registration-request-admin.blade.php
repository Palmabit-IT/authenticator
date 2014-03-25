<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Richiesta di registrazione su {{Config::get('authentication::app_name')}}</h2>
<div>
    <strong>The user: {{$body['email']}}</strong>
    <br/>
    has made an application for registration to the site. Check the profile and proceed with the activation
    <br/>
    <a href="#" target="_blank">vedi</a>
</div>
</body>
</html>