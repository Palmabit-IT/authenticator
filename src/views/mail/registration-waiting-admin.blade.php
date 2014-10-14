<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Registration request on {{Config::get('authentication::app_name')}}</h2>
<div>
    <strong>The user: {{$body['email']}}</strong>
    <br/>
    Sent you a registration request
    <br/>
    @if(! empty($body['comments']) )
        Comments : {{$body['comments']}}<br/>
    @endif
    <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', [ 'id' => $body['id'] ] )}}" target="_blank">See user</a>
</div>
</body>
</html>