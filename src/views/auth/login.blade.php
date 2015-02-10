@extends('authentication::layouts.baseauth')
@section('container')
{{Form::open(array('url' => URL::action("Palmabit\Authentication\Controllers\AuthController@postLogin"), 'method' => 'post', 'class' => 'form-signin') )}}
<h2 class="form-signin-heading">User Signin</h2>
@if($errors && ! $errors->isEmpty() )
@foreach($errors->all() as $error)
<div class="alert alert-danger">{{$error}}</div>
@endforeach
@endif
<?php $message = Session::get('message'); ?>
@if($message)
<div class="alert alert-success">{{$message}}</div>
@endif

<div class="form-group">
    <label for="email">Email</label>
    {{Form::text('email',null,['class'=>'form-control'])}}
    <label for="password">Password</label>
    {{Form::password('password',['class'=>'form-control'])}}
    {{Form::label('checkbox','Remember')}}
    {{Form::checkbox('checkbox',null, null)}}
    {{Form::submit('Login', array("class"=>"btn btn-lg btn-primary btn-block"))}}
    <div class="signin-btn">
        <a href="{{URL::to('user/signup')}}" alt="Sei già iscritto?">Don't have an account? Signup here</a><br>
        {{link_to_action('Palmabit\Authentication\Controllers\AuthController@getReminder','Forgot password?') }}<br>
        <a href="/">Go to website</a>
    <div>
</div>
        {{Form::close()}}
        @stop