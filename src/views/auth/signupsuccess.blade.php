@extends('authentication::layouts.baseauth')
@section ('title')
Registration request
@stop
@section('container')
<div class="row">
    <div class="col-lg-12 text-center v-center">
        <h1>Congratulations, you successfully sent a registration request on {{Config::get('authentication::app_name')}}</h1>
        <p>A moderator will validate your credential and activate your account soon.</p>
    </div>
</div>
@stop