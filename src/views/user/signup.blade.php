@extends('authentication::layouts.baseauth')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-4 col-md-offset-4">
              <div class="login-panel panel panel-default">
                  <div class="panel-heading">
                      <h3 class="panel-title">Registrazione</h3>
                  </div>
                  <div class="panel-body">
                      {{Form::open(["action" => "Palmabit\Authentication\Controllers\UserController@postSignupUser", "method" => "POST"])}}
                          <fieldset>
                              <div class="form-group">
                                  <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus autocomplete="off">
                                  <span class="text-danger">{{$errors->first('email')}}</span>

                              </div>
                              <div class="form-group">
                                  <input class="form-control" placeholder="Password" name="password" type="password" value="" autocomplete="off">
                                  <span class="text-danger">{{$errors->first('password')}}</span>

                              </div>
                              <div class="form-group">
                                  <input class="form-control" placeholder="Nome" name="first_name" type="text" autofocus autocomplete="off">
                                  <span class="text-danger">{{$errors->first('first_name')}}</span>
                              </div>
                              <div class="form-group">
                                  <input class="form-control" placeholder="Cognome" name="last_name" type="text" autofocus autocomplete="off">
                                  <span class="text-danger">{{$errors->first('last_name')}}</span>
                              </div>
                              {{Form::submit('Registrami', ["class" => "btn btn-lg btn-success btn-block"])}}
                          </fieldset>
                      {{Form::close()}}
                  </div>
              </div>
              <p>
                <a href="{{URL::to('user/login')}}" alt="Sei gi&agrave; iscritto?">Sei gi&agrave; iscritto? Autenticati qui</a><br><br>
                <a href="{{URL::to('/')}}" alt="Torna al sito">Torna al sito</a>
              </p>
          </div>
      </div>
  </div>

@stop