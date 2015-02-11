@extends('authentication::layouts.base-2cols')

@section('title')
    Admin area: groups list
@stop

@section('content')


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
    <h3>Grups list</h3>
    @if( ! $groups->isEmpty() )
        <ul class="list-group">
        @foreach($groups as $group)
            <li class="list-group-item">
                <span class="glyphicon glyphicon-user
"></span> {{$group->name}}
                @if(! $group->blocked)
                <a class ="pull-right margin-left-20" href="{{URL::action('Palmabit\Authentication\Controllers\GroupController@deleteGroup',['id' => $group->id, '_token' => csrf_token()])}}" ><span class="glyphicon glyphicon-trash margin-left-5 delete">cancella </span></a>
                <a class ="pull-right margin-left-20" href="{{URL::action('Palmabit\Authentication\Controllers\GroupController@editGroup', ['id' => $group->id])}}"><span class="glyphicon glyphicon-edit">edit </span></a>
                <span class="clearfix"></span>
                @endif
            </li>
            @endforeach
        </ul>
    @else
        <h5>There are no groups present.</h5>
    @endif
    <a href="{{URL::action('Palmabit\Authentication\Controllers\GroupController@editGroup')}}" class="btn btn-primary pull-right">Add new</a>
@stop

@section('footer_scripts')
    <script>
        $(".delete").click(function(){
            return confirm("Sei sicuro di volere eliminare l'elemento selezionato?");
        });
    </script>
@stop