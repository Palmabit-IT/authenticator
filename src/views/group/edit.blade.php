@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: edit user
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

    <h3><i class="glyphicon glyphicon-user"></i> edit gruppo</h3>
    <div class="col-md-6">
        {{-- group base form --}}
        <h3>Informazioni base</h3>
        {{Form::model($group, [ 'url' => [URL::action('Palmabit\Authentication\Controllers\GroupController@postEditGroup'), $group->id], 'method' => 'post'] ) }}
        {{FormField::name(["label" => "Nome:"])}}
        <span class="text-danger">{{$errors->first('name')}}</span>
        {{Form::hidden('id')}}
        <a href="{{URL::action('Palmabit\Authentication\Controllers\GroupController@deleteGroup',['id' => $group->id, '_token' => csrf_token()])}}" class="btn btn-danger pull-right margin-left-5 delete">Cancella</a>
        {{Form::submit('save', array("class"=>"btn btn-primary pull-right "))}}
        {{Form::close()}}
    </div>
    <div class="col-md-6">
        {{-- group permission form --}}
        <h3><span class="glyphicon glyphicon-lock"></span> permissions</h3>
        {{-- permission lists --}}
            @include('authentication::group.perm-list')
        {{-- add permission --}}
        {{Form::open(["route" => "groups.edit.permission","role"=>"form"])}}
        <div class="form-group">
            {{Form::label('permissions', 'Aggiungi permission:', ["class" => "control-label"])}}<br/>
            {{Form::select('permissions', $permission_values, '', ["class"=>"form-control permission-select"])}}
            <span class="text-danger">{{$errors->first('permission')}}</span>
            {{Form::hidden('id', $group->id)}}
            {{-- add permission operation --}}
            {{Form::hidden('operation', 1)}}
        </div>
        <div class="form-group">
            {{Form::submit('Aggiungi', ["class" => "btn btn-primary", ($group->exists) ? "" : "disabled"])}}
            @if(! $group->exists)
                <h5 style="color:gray">In order to associate permission you need to create the group first.</h5>
            @endif
        </div>
        {{Form::close()}}
    </div>

@stop

@section('footer_scripts')
<script>
    $(".delete").click(function(){
        return confirm("Sei sicuro di volere eliminare l'elemento selezionato?");
    });
</script>
@stop