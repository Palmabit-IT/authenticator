@extends('authentication::layouts.baseauth')
@section('container')
    {{Form::open(array('url' => URL::action("Palmabit\Authentication\Controllers\AuthController@postLogin"), 'method' => 'post', 'class' => 'form-signin') )}}
            <h2 class="form-signin-heading">Area riservata</h2>
            @if($errors && ! $errors->isEmpty() )
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger">{{$error}}</div>
                    @endforeach
            @endif

            {{FormField::email(array('label' => '', 'placeholder' => 'email'))}}
            {{FormField::password(array('label' => '', 'placeholder' => 'password'))}}
            {{Form::label('checkbox','Ricordami')}}
            {{Form::checkbox('checkbox',null, null)}}
            {{Form::submit('Login', array("class"=>"btn btn-lg btn-primary btn-block"))}}
            <div class="signin-btn">
                {{link_to_action('Palmabit\Authentication\Controllers\AuthController@getReminder','Dimenticato la password?') }}<br>
                <a href="/">Torna al sito</a>
            <div>
    {{Form::close()}}
@stop