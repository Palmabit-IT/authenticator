@extends('authentication::layouts.baseauth')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-4 col-md-offset-4">
              <div class="login-panel panel panel-success">
                  <div class="panel-heading">
                      <h3 class="panel-title">Conferma d'invio</h3>
                  </div>
                  <div class="panel-body">
                    <h1>Richiesta inviata con successo</h1>
                    <p class="error-para">La tua richiesta è stata inviata con successo. Riceverai subito un email di conferma con le istruzioni da seguire.<br> Grazie</p>
                    <br>
                    <a href="/" class="btn btn-lg btn-success btn-block">Torna al sito</a>
                  </div>
              </div>
          </div>
      </div>
  </div>

@stop