<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Registrazione su {{Config::get('authentication::app_name')}}</h2>
<div>
	<p>
		Grazie per esserti registrato, per accedere a tutte le FAQs devi essere un associato.<br>
		In questa email trovi tutte le informazioni necessarie.
	</p>
	<p>
    <ul>
        <li></li>
        <li></li>
    </ul>
	</p>

	<br>
    <a href="{{URL::to('/')}}" target="_blank">Vai al sito</a>
</div>
</body>
</html>