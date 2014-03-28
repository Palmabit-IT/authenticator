<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Richiesta di registrazione su {{Config::get('authentication::app_name')}}</h2>
	<div>
	    <p>
	    	L'utente: {{$body['email']}} si &egrave; registrato al portale.<br>
	    	Verifica il suo profilo nel pannello di controllo qui:  <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', array('id' => $body['id'])) }}" target="_blank">Vedi profilo</a>
	    </p>

	FISM

	<br>
	<a href="{{URL::to('/')}}" target="_blank">Vai alle FAQ</a>
	</div>
</body>
</html>