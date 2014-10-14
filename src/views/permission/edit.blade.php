@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: edit permission
@stop

@section('content')

<div class="row">
    {{-- model general errors from the form --}}
    @if($errors->has('model') )
    <div class="alert alert-danger">{{$errors->first('model')}}</div>
    @endif

    {{-- successful message --}}
    <?php $message = Session::get('message'); ?>
    @if( isset($message) )
    <div class="alert alert-success">{{$message}}</div>
    @endif
    <h3><i class="glyphicon glyphicon-lock"></i> edit permission</h3>

    {{Form::model($permission, [ 'url' => [URL::action('Palmabit\Authentication\Controllers\PermissionController@editPermission'), $permission->id], 'method' => 'post'] ) }}
    {{FormField::description(["label" => "Descrizione:", "type" => "text"])}}
    <span class="text-danger">{{$errors->first('description')}}</span>
    {{FormField::permission(["label" => "permission:"])}}
    <span class="text-danger">{{$errors->first('permission')}}</span>
    {{Form::hidden('id')}}
    <a href="{{URL::action('Palmabit\Authentication\Controllers\PermissionController@deletePermission',['id' => $permission->id, '_token' => csrf_token()])}}" class="btn btn-danger pull-right margin-left-5 delete">Cancella</a>
    {{Form::submit('save', array("class"=>"btn btn-primary pull-right "))}}
    {{Form::close()}}
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function(){
        return confirm("Are you sure?");
    });
</script>
@stop