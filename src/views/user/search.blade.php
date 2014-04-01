<div class="panel panel-default margin-top-20">
    <div class="panel-heading">
        <h3 class="panel-title">Ricerca utente</h3>
    </div>
    <div class="panel-body">
        {{Form::open(['action' => 'Palmabit\Authentication\Controllers\UserController@getList','method' => 'get'])}}
        {{FormField::email(['label' => 'email'])}}
        {{FormField::first_name(['label' => 'Nome:'])}}
        {{FormField::last_name(['label' => 'Cognome:'])}}
        {{FormField::billing_address_zip(['label' => 'Cap pagamento:'])}}
        {{FormField::code(['label' => 'Codice utente:'])}}
        <div class="form-group">
            {{Form::label('activated', 'Attivo: ')}}
            {{Form::select('activated', ['' => '', 1 => 'Sì', 0 => 'No'], Input::get('activated',''), ["class" => "form-control"])}}
        </div>
        {{Form::reset('Pulisci', ["class" => "btn btn-default"])}}
        {{Form::submit('Cerca', ["class" => "btn btn-primary"])}}
        {{Form::close()}}
    </div>
</div>