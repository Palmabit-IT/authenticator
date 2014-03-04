<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Benvenuto su {{Config::get('authentication::app_name')}}</h2>
<div>
    Buongiorno {{ $body['email'] }}
    <strong>Il tuo utente è stato attivato.</strong>
    <br/>
    <strong>Puoi effettuare il login al nostro sito usando l'email {{ $body['email']}} e la password da te inserita.</strong>
    <a href="{{URL::to('/')}}" target="_blank">Vai al sito</a>
</div>
</body>
</html>