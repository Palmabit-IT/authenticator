@extends('authentication::layouts.base-2cols')

@section('title')
    Admin area: permissions list
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
    <h3>Permissions list</h3>
    @if( ! $permissions->isEmpty() )
        <ul class="list-group">
        @foreach($permissions as $permission)
            <li class="list-group-item">
                <span class="glyphicon glyphicon-lock"></span> {{$permission->description}}
                @if(! $permission->blocked)
                <a class ="pull-right margin-left-20 delete" href="{{URL::action('Palmabit\Authentication\Controllers\PermissionController@deletePermission',['id' => $permission->id, '_token' => csrf_token()])}}" ><span class="glyphicon glyphicon-trash delete margin-left-20"></span> delete</a>
                <a class ="pull-right margin-left-20 " href="{{URL::action('Palmabit\Authentication\Controllers\PermissionController@editPermission', ['id' => $permission->id])}}"><span class="glyphicon glyphicon-edit margin-left-20"></span> edit</a>
                @endif
                <span class="clearfix"></span>
            </li>
            @endforeach
        </ul>
    @else
        <h5>No permissions present in the system.</h5>
    @endif
    <a href="{{URL::action('Palmabit\Authentication\Controllers\PermissionController@editPermission')}}" class="btn btn-primary pull-right">Add new</a>
@stop

@section('footer_scripts')
    <script>
        $(".delete").click(function(){
            return confirm("Are you sure?");
        });
    </script>
@stop