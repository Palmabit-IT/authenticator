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

    <h3><i class="glyphicon glyphicon-user"></i> Modifica profilo utente</h3>
    <hr/>
    <h3>Dati generali</h3>
    {{Form::model($user_profile,['route'=>'users.profile.edit', 'method' => 'post'])}}
        {{FormField::code()}}
        {{FormField::first_name(["label" => "nome:"])}}
        {{FormField::last_name(["lable" => "cognome:"])}}
        {{FormField::phone()}}
        {{FormField::vat()}}
        {{FormField::cf()}}
        {{FormField::billing_address()}}
        {{FormField::billing_address_zip()}}
        {{FormField::billing_state()}}
        {{FormField::billing_city()}}
        {{FormField::billing_country()}}
        {{FormField::shipping_address()}}
        {{FormField::shipping_address_zip()}}
        {{FormField::shipping_state()}}
        {{FormField::shipping_city()}}
        {{FormField::shipping_country()}}
        {{Form::hidden('user_id', $user_profile->user_id)}}
        {{Form::hidden('id', $user_profile->id)}}
        {{Form::submit('Salva',['class' =>'btn btn-primary pull-right margin-bottom-30'])}}
    {{Form::close()}}
</div>
@stop