<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Richiesta di registrazione su {{Config::get('authentication::app_name')}}</h2>
<div>
    <strong>L'utente: {{$body['email']}}</strong>
    <br/>
    Ha effettuato una richiesta di approvazione per il sito. Verifica il suo profilo prima di procedere con l'attivazione.
    <br/>
    @if(! empty($body['comments']) )
        Commenti : {{$body['comments']}}<br/>
    @endif
    <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', [ 'id' => $body['id'] ] )}}" target="_blank">Vedi utente</a>
</div>
</body>
</html>