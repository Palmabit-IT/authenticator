@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: lista utenti
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="col-md-8">
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
                        {{$user->email}} - {{($user->user_profile()->count()) ? ucfirst($user->user_profile()->first()->first_name): ''}} {{($user->user_profile()->count()) ? ucfirst($user->user_profile()->first()->last_name): ''}}
                        @if(! $user->blocked)
                        <div class="pull-right">
                            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editProfile', ['user_id' => $user->id])}}">
                                <span class="glyphicon glyphicon-user">profilo</span>
                            </a>&nbsp;
                            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', ['id' => $user->id])}}">
                                <span class="glyphicon glyphicon-edit">modifica</span>
                            </a>&nbsp;
                            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser',['id' => $user->id, '_token' => csrf_token()])}}" >
                                <span class="glyphicon glyphicon-trash margin-left-5 delete">cancella</span>
                            </a>&nbsp;
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
        <div class="col-md-4">
            @include('authentication::user.search')
        </div>
    </div>
</div>
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function(){
        return confirm("Sei sicuro di volere eliminare l'elemento selezionato?");
    });
</script>
@stop