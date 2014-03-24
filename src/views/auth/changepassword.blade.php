@extends('layouts.nobar')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Modifica password</h3>
                    </div>
                    <div class="panel-body">
                        <?php $message = Session::get('message'); ?>
                        @if( isset($message) )
                            <div class="alert alert-success">{{$message}}</div>
                        @endif
                        @if($errors && ! $errors->isEmpty() )
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{$error}}</div>
                            @endforeach
                        @endif
                        {{Form::open(array('url' => URL::action("Palmabit\Authentication\Controllers\AuthController@postChangePassword"), 'method' => 'post') )}}
                            <fieldset>
                            <div class="form-group">
                                  <input class="form-control" placeholder="Nuova password" name="password" type="text" autofocus>
                              </div>
                            {{Form::hidden('email',$email)}}
                            {{Form::hidden('token',$token)}}
                            {{Form::submit('Cambia', array("class"=>"btn btn-lg btn-success btn-block"))}}
                            </fieldset>
                        {{Form::close()}}
                    </div>
                </div>
                <p>
                    <a href="http://www.fism.net" alt="Torna al sito FISM">Torna al sito FISM</a>
                </p>
          </div>
      </div>
  </div>

@stop