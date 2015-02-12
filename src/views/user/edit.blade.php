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

    <div class="col-md-12">
        @if($user->id)
        <h3><i class="glyphicon glyphicon-user"></i> Edit user</h3>
        @else
        <h3><i class="glyphicon glyphicon-user"></i> New user</h3>
        @endif

        <h3>General info</h3>
        {{Form::model($user, [ 'url' =>
        [URL::action('Palmabit\Authentication\Controllers\UserController@postEditUser'),
        $user->id], 'method' => 'post'] ) }}
        {{-- Field hidden to fix chrome and safari autocomplete bug --}}
        {{Form::password('__to_hide_password_autocomplete', ['class' => 'hidden'])}}
        <div class="form-group">
            <label for="copyEmail">Email</label>
            {{Form::text('copyEmail',null,['class'=>'form-control','autocomplete' => 'off'])}}
        </div>
        <span class="text-danger">{{$errors->first('copyEmail')}}</span>

        <div class="form-group">
            {{Form::label('password','Password: ')}}
            {{Form::password('password',["autocomplete" => "off", "class" => "form-control"])}}
        </div>
        <span class="text-danger">{{$errors->first('password')}}</span>
        <div class="row">
            <div class="col-md-6">
                {{Form::label("activated","Active user")}}
                {{Form::select('activated', ["1" => "Yes", "0" => "No"], (isset($user->activated) &&
                $user->activated) ?
                $user->activated : "0", ["class"=> "form-control"] )}}
            </div>
            <div class="col-md-6">
                {{Form::label("preferred_lang","Preferred language")}}
                @include('authentication::layouts.partials.select_lang')
            </div>
        </div>
        {{Form::hidden('id')}}
        {{Form::hidden('form_name','user')}}
        <hr>
        <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser',['id' => $user->id, '_token' => csrf_token()])}}"
           class="btn btn-danger margin-left-5 delete">Delete</a>
        {{Form::submit('Save', array("class"=>"btn btn-primary pull-right"))}}
        {{Form::close()}}
    </div>
</div>
@if($user->id)
<div class="row">
    <div class="col-md-12">
        <h3><i class="glyphicon glyphicon-list-alt"></i> Group</h3>
        @include('authentication::user.groups')
    </div>
</div>
@endif
</div>
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function () {
        return confirm("Are you sure?");
    });
</script>
@stop