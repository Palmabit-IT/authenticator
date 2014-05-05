@extends('authentication::layouts.baseauth')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-4 col-md-offset-4">
              <div class="login-panel panel panel-success">
                  <div class="panel-heading">
                      <h3 class="panel-title">Conferma modifica password.</h3>
                  </div>
                  <div class="panel-body">
                    <h1>Password modificata con successo</h1>
                    <p class="error-para">La tua password è stata modificata con successo.<br> Grazie</p>
                    <br>
                    <a href="/" class="btn btn-lg btn-success btn-block">Torna alle faqs</a>
                  </div>
              </div>
          </div>
      </div>
  </div>

@stop