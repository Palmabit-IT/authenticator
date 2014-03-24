@extends('layouts.nobar')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Recupera password</h3>
                </div>
                @if($errors && ! $errors->isEmpty() )
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{$error}}</div>
                @endforeach
                @endif
                <div class="panel-body">
                    {{Form::open(array('url' => URL::action("Palmabit\Authentication\Controllers\AuthController@postReminder"), 'method' => 'post') )}}
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        {{Form::submit('Recupera', ['class' => 'btn btn-lg btn-success btn-block'])}}
                    </fieldset>
                    {{Form::close()}}
                </div>
            </div>
            <p>
                <a href="{{URL::to('/user/login')}}" alt="Sei qui per errore?">Sei qui per errore?</a><br>
                <a href="{{URL::to('/user/signup')}}" alt="Non sei ancora registrato?">Non sei ancora registrato?</a><br><br>
                <a href="http://www.fism.net" alt="Torna al sito FISM">Torna al sito FISM</a>
            </p>
        </div>
    </div>
</div>

@stop