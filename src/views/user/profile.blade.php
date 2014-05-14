@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: modifica profilo utente
@stop

@section('content')

<div class="row">
    {{-- successful message --}}
    <?php $message = Session::get('message'); ?>
    @if( isset($message) )
    <div class="alert alert-success">{{$message}}</div>
    @endif
    @if( $errors->has('model') )
        <div class="alert alert-danger">{{$errors->first('model')}}</div>
    @endif

    <h3><i class="glyphicon glyphicon-user"></i> Modifica profilo utente</h3>
    <hr/>
    {{Form::model($user_profile,['route'=>'users.profile.edit', 'method' => 'post'])}}
        <div class="row">
            <div class="col-md-6">
                {{FormField::code()}}
                {{FormField::new_password(["label" => "Password:", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('password')}}</span>
                {{FormField::first_name(["label" => "Nome:", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('first_name')}}</span>
                {{FormField::last_name(["label" => "Cognome:", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('last_name')}}</span>
            </div>
            <div class="col-md-6">
                {{FormField::phone(["label" => "Telefono:", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('phone')}}</span>
                <div class="form-group">
                    {{Form::label('profile_type', 'Tipo di profilo:', ["class" => "control-label"])}}<br/>
                    {{Form::select('profile_type', [
                    "" => "",
                    "Special Effects Company" => "Special Effects Company",
                    "Manufacturer" => "Manufacturer",
                    "NightClub" => "NightClub",
                    "Production Company" => "Production Company",
                    "Full service" => "Full service",
                    "Retail Store" => "Retail Store",
                    "Theater" => "Theater",
                    "Theme Parks" => "Theme Parks",
                    "Trade Show" => "Trade Show",
                    "Film Company" => "Film Company",
                    "End user" => "End user",
                    ], $user_profile->profile_type, ["class"=>"form-control"])}}
                    <span class="text-danger">{{$errors->first('profile_type')}}</span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h4>Informazioni di fatturazione</h4>
                {{FormField::company(["label" => "Ragione sociale:", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('company')}}</span>
                {{FormField::vat(["label" => "Partita IVA", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('vat')}}</span>
                {{FormField::cf(["label" => "Codice fiscale", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('cf')}}</span>
                {{FormField::billing_address(["label" => "Indirizzo di fatturazione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('billing_address')}}</span>
                {{FormField::billing_city(["label" => "Città fatturazione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('billing_city')}}</span>
                {{FormField::billing_address_zip(["label" => "CAP fatturazione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('billing_address_zip')}}</span>
                {{FormField::billing_state(["label" => "Provincia fatturazione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('billing_country')}}</span>
                {{FormField::billing_country(["label" => "Nazione fatturazione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('billing_state')}}</span>

            </div>
            <div class="col-md-6">
                <h4>Informazioni di spedizione</h4>
                {{FormField::shipping_address(["label" => "Indirizzo di spedizione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('shipping_address')}}</span>
                {{FormField::shipping_city(["label" => "Città spedizione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('shipping_city')}}</span>
                {{FormField::shipping_address_zip(["label" => "CAP spedizione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('shipping_address_zip')}}</span>
                {{FormField::shipping_state(["label" => "Provincia spedizione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('shipping_country')}}</span>
                {{FormField::shipping_country(["label" => "Nazione spedizione", "autocomplete" => "off"])}}
                <span class="text-danger">{{$errors->first('shipping_state')}}</span>


            </div>
        </div>
        <hr>
        <div class="row">
             <div class="col-md-6">
                {{Form::hidden('user_id', $user_profile->user_id)}}
                {{Form::hidden('id', $user_profile->id)}}
                {{Form::submit('Salva',['class' =>'btn btn-primary margin-bottom-30'])}}
            </div>
        </div>
    {{Form::close()}}
</div>
@stop
