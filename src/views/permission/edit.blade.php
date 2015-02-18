@extends('authentication::layouts.base-2cols')

@section('title')
@if($permission->id)
Admin area: edit permission
@else
Admin area: new permission
@endif
@stop

@section('content')


{{-- model general errors from the form --}}
@if($errors->has('model') )
<div class="alert alert-danger">{{$errors->first('model')}}</div>
@endif

{{-- successful message --}}
<?php $message = Session::get('message'); ?>
@if( isset($message) )
<div class="alert alert-success">{{$message}}</div>
@endif
@if($permission->id)
<h3><i class="glyphicon glyphicon-lock"></i> edit permission</h3>
@else
<h3><i class="glyphicon glyphicon-lock"></i> new permission</h3>
@endif
{{-- print errors --}}
@if($errors && ! $errors->isEmpty() )
@foreach($errors->all() as $error)
<div class="alert alert-danger">{{$error}}</div>
@endforeach
@endif

{{Form::model($permission, [ 'url' => [URL::action('Palmabit\Authentication\Controllers\PermissionController@editPermission'), $permission->id], 'method' => 'post'] ) }}
<div class="form-group">
    <label for="description">Description:</label>
    {{Form::text('description',null,['class'=>'form-control'])}}
</div>
<span class="text-danger">{{$errors->first('description')}}</span>
<div class="form-group">
    <label for="permission">Permission:</label>
    {{Form::text('permission',null,['class'=>'form-control'])}}
</div>
<span class="text-danger">{{$errors->first('permission')}}</span>
{{Form::hidden('id')}}
@if($permission->id)
<a href="{{URL::action('Palmabit\Authentication\Controllers\PermissionController@deletePermission',['id' => $permission->id, '_token' => csrf_token()])}}"
   class="btn btn-danger pull-left delete margin-top-10">Delete</a>
@endif
{{Form::submit('save', array("class"=>"btn btn-primary pull-right margin-top-10"))}}
{{Form::close()}}
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function () {
        return confirm("Are you sure?");
    });
</script>
@stop