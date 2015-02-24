<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Application for {{Config::get('authentication::app_name')}}</h2>

<div>
    <strong>The request for registration has been submitted successfully. A moderator will validate the data you have
        entered</strong>
    <br/>
    <strong>Summary data: </strong>
    <ul>
        <li>Username: {{$body['email']}}</li>
        <li>Password: {{$body['password']}}</li>
    </ul>
</div>
</body>
</html>