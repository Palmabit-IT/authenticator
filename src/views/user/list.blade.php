@extends('authentication::layouts.base-1cols')

@section('title')
    Admin area: lista utenti
@stop

@section('content')

<div class="row">
    {{-- print messages --}}
    <?php $message = Session::get('message'); ?>
    @if( isset($message) )
    <div class="alert alert-success">{{$message}}</div>
    @endif
    {{-- print errors --}}
    @if($errors && ! $errors->isEmpty() )
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">{{$error}}</div>
    @endforeach
    @endif
    <h3>Lista utenti</h3>
    @if($users)
        <ul class="list-group">
        @foreach($users as $user)
            <li class="list-group-item">
                <span class="
glyphicon glyphicon-comment"></span> {{$user->email}} <span class="glyphicon glyphicon-user
"></span> {{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}}
                <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser',['id' => $user->id, '_token' => csrf_token()])}}" ><span class="glyphicon glyphicon-trash pull-right margin-left-5">cancella </span></a>
                <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', ['id' => $user->id])}}"><span class="glyphicon glyphicon-cog pull-right">modifica </span></a>
                <span class="clearfix"></span>
            </li>
            @endforeach
        </ul>
    @else
        <h5>Non ci sono utenti presenti nel sistema.</h5>
    @endif
    <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser')}}" class="btn btn-primary pull-right">Add</a>
</div>
@stop