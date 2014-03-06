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
    <h3>Dati generali</h3>
    {{Form::model($user_profile,['route'=>'users.profile.edit', 'method' => 'post'])}}
        {{FormField::code()}}
        {{FormField::new_password(["label" => "password:"])}}
        <span class="text-danger">{{$errors->first('password')}}</span>
        {{FormField::first_name(["label" => "nome:"])}}
        <span class="text-danger">{{$errors->first('first_name')}}</span>
        {{FormField::last_name(["label" => "cognome:"])}}
        <span class="text-danger">{{$errors->first('last_name')}}</span>
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
        {{FormField::phone(["label" => "telefono:"])}}
        <span class="text-danger">{{$errors->first('phone')}}</span>
        {{FormField::vat(["label" => "partita iva"])}}
        <span class="text-danger">{{$errors->first('vat')}}</span>
        {{FormField::cf(["label" => "codice fiscale"])}}
        <span class="text-danger">{{$errors->first('cf')}}</span>
        {{FormField::billing_state(["label" => "stato fatturazione"])}}
        <span class="text-danger">{{$errors->first('billing_state')}}</span>
        {{FormField::billing_city(["label" => "provincia fatturazione"])}}
        <span class="text-danger">{{$errors->first('billing_city')}}</span>
        {{FormField::billing_country(["label" => "paese fatturazione"])}}
        <span class="text-danger">{{$errors->first('billing_country')}}</span>
        {{FormField::billing_address_zip(["label" => "cap fatturazione"])}}
        <span class="text-danger">{{$errors->first('billing_address_zip')}}</span>
        {{FormField::billing_address(["label" => "indirizzo di fatturazione"])}}
        <span class="text-danger">{{$errors->first('billing_address')}}</span>
        {{FormField::shipping_state(["label" => "stato spedizione"])}}
        <span class="text-danger">{{$errors->first('shipping_state')}}</span>
        {{FormField::shipping_city(["label" => "provincia spedizione"])}}
        <span class="text-danger">{{$errors->first('shipping_city')}}</span>
        {{FormField::shipping_country(["label" => "paese spedizione"])}}
        <span class="text-danger">{{$errors->first('shipping_country')}}</span>
        {{FormField::shipping_address_zip(["label" => "cap spedizione"])}}
        <span class="text-danger">{{$errors->first('shipping_address_zip')}}</span>
        {{FormField::shipping_address(["label" => "indirizzo di spedizione"])}}
        <span class="text-danger">{{$errors->first('shipping_address')}}</span>
        {{Form::hidden('user_id', $user_profile->user_id)}}
        {{Form::hidden('id', $user_profile->id)}}
        {{Form::submit('Salva',['class' =>'btn btn-primary pull-right margin-bottom-30'])}}
    {{Form::close()}}
</div>
@stop
