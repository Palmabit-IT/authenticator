@extends('authentication::layouts.base-1cols')

@section('title')
Admin area: lista utenti
@stop

@section('content')

<div class="row">
    <?php $message = Session::get('message'); ?>
    @if( isset($message) )
    <div class="alert alert-success">{{$message}}</div>
    @endif
    <h3><i class="glyphicon glyphicon-user"></i> Modifica utente</h3>

    {{Form::model($user, [ 'url' => [URL::action('Palmabit\Authentication\Controllers\UserController@postEditUser'), $user->id], 'method' => 'post'] ) }}
    {{FormField::email(["autocomplete" => "off"])}}
    <span class="text-danger">{{$errors->first('email')}}</span>
    {{FormField::password()}}
    <span class="text-danger">{{$errors->first('password')}}</span>
    {{FormField::last_name( ["autocomplete" => "off", "label" => "Nome"] ) }}
    <span class="text-danger">{{$errors->first('last_name')}}</span>
    {{FormField::first_name( ["label" => "Cognome"] ) }}
    <span class="text-danger">{{$errors->first('first_name')}}</span>
    <div class="form-group">
        {{Form::label("activated","Utente attivo")}}
        {{Form::select('activated', ["1" => "Sì", "0" => "No"], (isset($user->activated) && $user->activated) ? $user->activated : "0", ["class"=> "form-control"] )}}
    </div>
    {{Form::hidden('id')}}
    {{Form::submit('Salva', array("class"=>"btn btn-primary pull-right "))}}
    {{Form::close()}}
@stop