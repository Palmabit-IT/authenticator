<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
	<h2>Benvenuto in {{Config::get('authentication::app_name')}}</h2>
	<div>
		<p>
		    Gentile {{ $body['email'] }} Il tuo profilo &egrave; ora attivo. Adesso puoi autenticarti al portale via username e password da te scelti.
		</p>

	FISM

	<br>
	<a href="{{URL::to('/')}}" target="_blank">Vai alle FAQ</a>
	</div>
</body>
</html>