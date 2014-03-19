<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Richiesta di registrazione su {{Config::get('authentication::app_name')}}</h2>
<div>
    <strong>{{L::t('The user')}}: {{$body['email']}}</strong>
    <br/>
    {{L::t('has made an application for registration to the site. Check the profile and proceed with the activation')}}.
    <br/>
    @if(! empty($body['comments']) )
        {{L::t('Comments')}} : {{$body['comments']}}<br/>
    @endif
    <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', [ 'id' => $body['id'] ] )}}" target="_blank">{{L::t('View user')}}</a>
</div>
</body>
</html>