@extends('authentication::layouts.baseauth')
@section('container')
    {{Form::open(array('url' => URL::action("Palmabit\Authentication\Controllers\AuthController@postLogin"), 'method' => 'post', 'class' => 'form-signin') )}}
            <h2 class="form-signin-heading">User Signin</h2>
            @if($errors && ! $errors->isEmpty() )
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger">{{$error}}</div>
                    @endforeach
            @endif

            {{FormField::email(array('label' => '', 'placeholder' => 'email'))}}
            {{FormField::password(array('label' => '', 'placeholder' => 'password'))}}
            {{Form::label('checkbox','Remember')}}
            {{Form::checkbox('checkbox',null, null)}}
            {{Form::submit('Login', array("class"=>"btn btn-lg btn-primary btn-block"))}}
            <div class="signin-btn">
                <a href="{{URL::to('user/signup')}}" alt="Sei gi&agrave; iscritto?">Don't have an account? Signup here</a><br>
                {{link_to_action('Palmabit\Authentication\Controllers\AuthController@getReminder','Forgot password?') }}<br>
                <a href="/">Go to website</a>
            <div>
    {{Form::close()}}
@stop