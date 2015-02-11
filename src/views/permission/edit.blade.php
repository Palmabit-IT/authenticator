@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: edit permission
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
    <h3><i class="glyphicon glyphicon-lock"></i> edit permission</h3>

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
    <a href="{{URL::action('Palmabit\Authentication\Controllers\PermissionController@deletePermission',['id' => $permission->id, '_token' => csrf_token()])}}" class="btn btn-danger pull-left delete">Delete</a>
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