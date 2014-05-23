@extends('authentication::layouts.baseauth')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-4 col-md-offset-4">
              <div class="login-panel panel panel-default">
                  <div class="panel-heading">
                      <h3 class="panel-title">Login</h3>
                  </div>
                  <div class="panel-body">
                    @if($errors && ! $errors->isEmpty() )
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{$error}}</div>
                            @endforeach
                    @endif
                    {{Form::open(array('url' => URL::action("Palmabit\Authentication\Controllers\AuthController@postLogin"), 'method' => 'post', 'class' => 'form-signin') )}}
                        <fieldset>
                              <div class="form-group">
                                  <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                              </div>
                              <div class="form-group">
                                  <input class="form-control" placeholder="Password" name="password" type="password" value="">
                              </div>
                              <div class="checkbox">
                                  <label>
                                      <input name="remember" type="checkbox" value="Ricordami">Ricordami
                                  </label>
                              </div>
                              <!-- Change this to a button or input when using this as a form -->
                            {{Form::submit('Login', array("class"=>"btn btn-lg btn-primary btn-block"))}}
                        </fieldset>
                    {{Form::close()}}
                  </div>
              </div>
              <p>
                {{link_to_action('Palmabit\Authentication\Controllers\AuthController@getReminder','Password dimenticata?') }}<br>
                <a href="/user/signup" alt="Non sei ancora registrato?">Non sei ancora registrato?</a><br><br>
                <a href="{{URL::to('/')}}" alt="Torna al sito">Torna al sito</a>
              </p>
          </div>
      </div>
  </div>

@stop