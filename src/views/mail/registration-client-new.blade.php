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
		In questa email trovi tutte le informazioni necessarie per entrare a far parte di FISM.
	</p>
	<p>
    <ul>
        <li></li>
        <li></li>
    </ul>
	</p>
	FISM

	<br>
	<a href="{{URL::to('/')}}" target="_blank">Torna alle FAQ</a>
</div>
</body>
</html>