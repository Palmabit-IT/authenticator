<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Registrazione su {{Config::get('authentication::app_name')}}</h2>
<div>
    <p>
    	La richiesta di registrazione &egrave; avvenuta con successo. Non puoi ancora accedere alle FAQs fino al momento del rinnovo associato
    </p>
    <p>
    	Riepilogo dei tuoi dati:
	    <ul>
	        <li>Username: {{$body['email']}}</li>
	        <li>Password: {{$body['password']}}</li>
	    </ul>
	</p>
    FISM

    <br>
    <a href="{{URL::to('/')}}" target="_blank">Vai alle FAQ</a>
</div>
</body>
</html>