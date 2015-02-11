@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: edit user
@stop

@section('content')

<div class="row">
    {{-- successful message --}}
    <?php $message = Session::get('message'); ?>
    @if( isset($message) )
        <div class="alert alert-success">{{$message}}</div>
    @endif

    <h3><i class="glyphicon glyphicon-user"></i> Edit user</h3>
    <div class="col-md-6">
        <h3>General info</h3>
        {{Form::model($user, [ 'url' => [URL::action('Palmabit\Authentication\Controllers\UserController@postEditUser'), $user->id], 'method' => 'post'] ) }}
        {{-- Field hidden to fix chrome and safari autocomplete bug --}}
        {{Form::password('__to_hide_password_autocomplete', ['class' => 'hidden'])}}
        <div class="form-group">
            <label for="copyEmail">Email</label>
            {{Form::text('copyEmail',null,['class'=>'form-control','autocomplete' => 'off'])}}
        </div>
        <span class="text-danger">{{$errors->first('copyEmail')}}</span>
        <div class="form-group">
        {{Form::label('password','password: ')}}
        {{Form::password('password',["autocomplete" => "off", "class" => "form-control"])}}
        </div>
        <span class="text-danger">{{$errors->first('password')}}</span>
        {{--    {{FormField::last_name( ["label" => "Nome", "autocomplete" => "off"] ) }}
        <span class="text-danger">{{$errors->first('last_name')}}</span>
        <div class="form-group">
            <label for="first_name">Cognome</label>
            {{Form::text('first_name',null,['class'=>'form-control','autocomplete' => 'off'])}}
        </div>
        <span class="text-danger">{{$errors->first('first_name')}}</span> --}}
        <div class="form-group">
            {{Form::label("activated","Active user")}}
            {{Form::select('activated', ["1" => "Yes", "0" => "No"], (isset($user->activated) && $user->activated) ? $user->activated : "0", ["class"=> "form-control"] )}}
        </div>
        <div class="form-group">
        {{Form::label("preferred_lang","Preferred language")}}
        @include('authentication::layouts.partials.select_lang')
        </div>
        {{Form::hidden('id')}}
        {{Form::hidden('form_name','user')}}
        <hr>
        {{Form::submit('save', array("class"=>"btn btn-primary"))}}
        <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser',['id' => $user->id, '_token' => csrf_token()])}}" class="btn btn-danger margin-left-5 delete">Delete</a>
        {{Form::close()}}
    </div>
    <div class="col-md-6">
        <h3>Group</h3>
        @include('authentication::user.groups')
    </div>
</div>
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function(){
        return confirm("Are you sure?");
    });
</script>
@stop