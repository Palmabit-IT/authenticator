@extends('authentication::layouts.baseauth')
@section('container')

<div class="row margin-top-30">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Signup</h3>
            </div>
            <div class="panel-body">
                @if($errors && ! $errors->isEmpty() )
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{$error}}</div>
                @endforeach
                @endif
                {{Form::open(["route" => "user.signup"])}}
                <fieldset>
                    <div class="form-group">
                        {{Form::email('email',null,['class'=>'form-control','placeholder'=>'E-mail','autofocus','autocomplete'=>'off'])}}
                        {{--<span class="text-danger">{{$errors->first('email')}}</span>--}}
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Password" name="password" type="password" value=""
                               autocomplete="off">
                        {{--<span class="text-danger">{{$errors->first('password')}}</span>--}}
                    </div>
                    <div class="form-group">
                        {{Form::text('first_name',null,['class'=>'form-control','placeholder'=>'First name','autofocus','autocomplete'=>'off'])}}
                        {{--<span class="text-danger">{{$errors->first('first_name')}}</span>--}}
                    </div>
                    <div class="form-group">
                        {{Form::text('last_name',null,['class'=>'form-control','placeholder'=>'Last name','autofocus','autocomplete'=>'off'])}}
                        {{--<span class="text-danger">{{$errors->first('last_name')}}</span>--}}
                    </div>
                    @include('authentication::layouts.partials.select_lang')
                    <!--                              <div class="form-group">-->
                    <!--                                  <input class="form-control" placeholder="Nome" name="first_name" type="text" autofocus autocomplete="off">-->
                    <!--                                  <span class="text-danger">{{$errors->first('first_name')}}</span>-->
                    <!--                              </div>-->
                    <!--                              <div class="form-group">-->
                    <!--                                  <input class="form-control" placeholder="Cognome" name="last_name" type="text" autofocus autocomplete="off">-->
                    <!--                                  <span class="text-danger">{{$errors->first('last_name')}}</span>-->
                    <!--                              </div>-->
                    {{Form::submit('Signup', ["class" => "btn btn-lg btn-primary btn-block"])}}
                </fieldset>
                {{Form::close()}}
            </div>
        </div>
        <p>
            <a href="{{URL::to('user/login')}}" alt="Sei gi&agrave; iscritto?">Already registered? signin here</a><br>
            <a href="/">Go to website</a>
        </p>
    </div>
</div>

@stop