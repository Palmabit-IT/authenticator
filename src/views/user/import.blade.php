@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: importazione utenti
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        {{-- successful message --}}
        <?php $message = Session::get('message'); ?>
        @if( isset($message) )
            <div class="alert alert-success">{{$message}}</div>
        @endif

        <h3><i class="glyphicon glyphicon-file"></i> Importazione utente *:</h3>
        <div class="col-md-6">
            {{Form::open([ 'action' => 'Palmabit\Authentication\Controllers\UserController@postImport', 'method' => 'post', 'files' => true])}}
                <div class="form-group">
                    {{Form::file('file')}}
                    <span class="text text-danger">{{$errors->first('file')}}</span>
                </div>
                <br/>
                {{Form::submit('Importa', array("class"=>"btn btn-primary"))}}
            {{Form::close()}}
            <br>
        </div>
        <div class="col-md-6">
        </div>
    </div>
</div>
@stop