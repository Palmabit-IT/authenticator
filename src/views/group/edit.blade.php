@extends('authentication::layouts.base-2cols')

@section('title')
@if($group->id)
Admin area: edit group
@else
Admin area: new group
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
<div class="row">
    <div class="col-md-12">
        @if($group->id)
        <h3><i class="glyphicon glyphicon-list-alt"></i> Edit group</h3>
        @else
        <h3><i class="glyphicon glyphicon-list-alt"></i> New group</h3>
        @endif

        {{-- group base form --}}
        {{Form::model($group, [ 'url' =>
        [URL::action('Palmabit\Authentication\Controllers\GroupController@postEditGroup'), $group->id], 'method' =>
        'post'] ) }}

        <div class="form-group">
            <label for="name">Name:</label>
            {{Form::text('name',null,['class'=>'form-control'])}}
        </div>

        <span class="text-danger">{{$errors->first('name')}}</span>
        {{Form::hidden('id')}}
        <a href="{{URL::action('Palmabit\Authentication\Controllers\GroupController@deleteGroup',['id' => $group->id, '_token' => csrf_token()])}}"
           class="btn btn-danger pull-left delete margin-top-10">Cancella</a>
        {{Form::submit('save', array("class"=>"btn btn-primary pull-right margin-top-10"))}}
        {{Form::close()}}
    </div>
</div>
<div class="row margin-top-20">
    @if($group->id)
    <div class="col-md-12">
        <h3><span class="glyphicon glyphicon-lock"></span> Permissions Group</h3>

        {{-- add permission --}}
        <div class="form-select">
            {{Form::open(["route" => "groups.edit.permission","role"=>"form"])}}
            <div class="form-group">
                {{Form::label('permissions', 'Add permission:', ["class" => "control-label"])}}<br/>
                {{Form::select('permissions', $permission_values, '', ["class"=>"form-control permission-select"])}}
                <span class="text-danger">{{$errors->first('permission')}}</span>
                {{Form::hidden('id', $group->id)}}
                {{-- add permission operation --}}
                {{Form::hidden('operation', 1)}}
            </div>
            <div class="form-group">
                {{Form::submit('Aggiungi', ["class" => " pull-right btn btn-primary margin-top-10", ($group->exists) ?
                "" : "disabled"])}}
                @if(! $group->exists)
                <h5 style="color:gray">In order to associate permission you need to create the group first.</h5>
                @endif
            </div>
            {{Form::close()}}
        </div>
        {{-- group permission form --}}
        {{-- permission lists --}}
        @include('authentication::group.perm-list')
    </div>
    @endif
</div>
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function () {
        return confirm("Sei sicuro di volere eliminare l'elemento selezionato?");
    });
</script>
@stop