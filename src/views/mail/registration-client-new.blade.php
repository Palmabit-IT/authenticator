<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Registrazione su {{Config::get('authentication::app_name')}}</h2>
<div>
    <p>Buongiorno,</p>
	<p>
        Per poter usufruire dei contenuti dell’Area Domande/Risposte di FISM è necessario prima procedere con l’associazione.
        Per ulteriori informazioni in merito all’affiliazione è possibile contattarci a questo numero di telefono:06 6987 0511 oppure via mail all’indirizzo: fismnazionale@tin.it
	</p>
	<p>
        Cordiali Saluti
    </p>

    <a href="{{URL::to('/')}}" target="_blank">Vai alle FAQ</a>
</div>
</body>
</html>