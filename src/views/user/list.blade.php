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

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <h3 style="margin: 5px 0px 0px 0px;">Utenti</h3>
            </div>
            <div class="col-md-6">
                 <form action="{{URL::action('Palmabit\Authentication\Controllers\UserController@getList')}}" class="form-inline" role="form" >
                    <div class="form-group col-md-8">
                        <select class="form-control col-md-12" style="width:100%" name="q">
                          <option value="all" <?= ($q=='all')?'selected':''?> >Tutti</option>
                          <option value="new" <?= ($q=='new')?'selected':''?>>Nuovi</option>
                          <option value="noninregola" <?= ($q=='noninregola')?'selected':''?>>Non in regola</option>
                          <option value="inregola" <?= ($q=='inregola')?'selected':''?>>In Regola</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default col-md-4">Filtra</button>
                </form>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            @if(! $users->isEmpty() )
                <ul class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item">
                            @if($user->activated && ! $user->new_user)
                                <span class="badge badge-green">&nbsp;</span>&nbsp;&nbsp;
                            @elseif($user->activated && $user->new_user)
                                <span class="badge badge-orange">&nbsp;</span>&nbsp;&nbsp;
                            @else
                                <span class="badge badge-red">&nbsp;</span>&nbsp;&nbsp;
                            @endif
                            {{$user->email}} - {{$user->first_name}} - {{$user->last_name}}
                            @if(! $user->blocked)
                            <div class="pull-right">
                                <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', ['id' => $user->id])}}"><i class="glyphicon glyphicon-edit"></i> modifica</a>&nbsp;
                                <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser',['id' => $user->id, '_token' => csrf_token()])}}" class="delete"><i class="glyphicon glyphicon-trash margin-left-5"></i> cancella</a>&nbsp;
                            </div>
                            @endif
                            <span class="clearfix"></span>
                        </li>
                    @endforeach
                </ul>
            {{-- pagination links --}}
            {{$users->links()}}
            @else
                <h5>Non &egrave; stato trovato alcun utente nel sistema.</h5>
            @endif
            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser')}}" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
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