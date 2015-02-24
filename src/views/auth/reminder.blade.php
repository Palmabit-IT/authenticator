@extends('authentication::layouts.baseauth')
@section('container')
<h1>Recupero password</h1>
<?php $message = Session::get('message'); ?>
@if( isset($message) )
<div class="alert alert-success">{{$message}}</div>
@endif
@if($errors && ! $errors->isEmpty() )
@foreach($errors->all() as $error)
<div class="alert alert-danger">{{$error}}</div>
@endforeach
@endif
{{Form::open(array('url' => URL::action("Palmabit\Authentication\Controllers\AuthController@postReminder"), 'method' => 'post') )}}
<div class="form-group">
    <label for="email">Email</label>
    {{Form::text('email',null,['class'=>'form-control'])}}
</div>
{{Form::submit('Invia', array("class"=>"btn btn-large btn-primary"))}}
{{Form::close()}}
@stop