@extends('authentication::layouts.base-2cols')

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
    <h3>Utenti</h3>
    @if(! $users->isEmpty() )
    <ul class="list-group">
        @foreach($users as $user)
            <li class="list-group-item">
                <span class="badge {{$user->activated ? 'badge-green' : 'badge-red'}}">&nbsp;</span>&nbsp;&nbsp;
                {{$user->email}} - {{$user->first_name}} - {{$user->last_name}}
                @if(! $user->blocked)
                <div class="pull-right">
                    <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', ['id' => $user->id])}}"><i class="glyphicon glyphicon-edit"></i> modifica</a>&nbsp;
                    <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser',['id' => $user->id, '_token' => csrf_token()])}}" ><i class="glyphicon glyphicon-trash margin-left-5 delete"></i> cancella</a>&nbsp;
                </div>
                @endif
                <span class="clearfix"></span>
            </li>
            @endforeach
        </ul>
    {{-- pagination links --}}
    {{$users->links()}}

    @else
    <h5>Non ci sono utenti presenti nel sistema.</h5>
    @endif
    <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser')}}" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
</div>
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function(){
        return confirm("Sei sicuro di volere eliminare l'elemento selezionato?");
    });
</script>
@stop