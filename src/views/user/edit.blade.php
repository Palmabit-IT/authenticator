@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: modifica utenti
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        {{-- successful message --}}
        <?php $message = Session::get('message'); ?>
        @if( isset($message) )
            <div class="alert alert-success">{{$message}}</div>
        @endif
    <h3><i class="glyphicon glyphicon-user"></i> Gestione utente</h3>
    <div class="col-md-6">
        <h3>Dati generali</h3>
        {{Form::model($user, [ 'url' => [URL::action('Palmabit\Authentication\Controllers\UserController@postEditUser'), $user->id], 'method' => 'post'] ) }}
        {{FormField::copyEmail(["autocomplete" => "off", "label" => "email"])}}
        <span class="text-danger">{{$errors->first('copyEmail')}}</span>
        <div class="form-group">
        {{Form::label('password','password: ')}}
        {{Form::text('password','',["autocomplete" => "off", "class" => "form-control"])}}
        </div>
        <span class="text-danger">{{$errors->first('password')}}</span>
        {{--    {{FormField::last_name( ["label" => "Nome", "autocomplete" => "off"] ) }}
        <span class="text-danger">{{$errors->first('last_name')}}</span>
        {{FormField::first_name( ["label" => "Cognome", "autocomplete" => "off"] ) }}
        <span class="text-danger">{{$errors->first('first_name')}}</span> --}}
        <div class="form-group">
            {{Form::label("activated","Utente attivo")}}
            {{Form::select('activated', ["1" => "Sì", "0" => "No"], (isset($user->activated) && $user->activated) ? $user->activated : "0", ["class"=> "form-control"] )}}
        </div>
    </div>
</div>
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function(){
        return confirm("Sei sicuro di volere eliminare l'elemento selezionato?");
    });
</script>
@stop