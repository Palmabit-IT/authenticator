@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: edit user profile
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

    <h3><i class="glyphicon glyphicon-user"></i> edit user profile</h3>
    <hr/>
    @if($errors->first('permissionNotAllowed'))
    <div class="alert alert-danger" role="alert">
        <span>{{$errors->first('permissionNotAllowed')}}</span>
    </div>
    @endif
    {{Form::model($user_profile,['route'=>'users.profile.edit', 'method' => 'post'])}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="code">Code</label>
                {{Form::text('code',null,['class'=>'form-control', 'autocomplete' => 'off'])}}
            </div>

            <span class="text-danger">{{$errors->first('password')}}</span>

            <div class="form-group">
                <label for="first_name">First name:</label>
                {{Form::text('first_name',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('first_name')}}</span>

            <div class="form-group">
                <label for="last_name">Last name:</label>
                {{Form::text('last_name',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('last_name')}}</span>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">Phone number:</label>
                {{Form::text('phone',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('phone')}}</span>

            <div class="form-group">
                {{Form::label('profile_type', 'Profile type:', ["class" => "control-label"])}}<br/>
                {{Form::select('profile_type', $profile_type, $user_profile->profile_type, ["class"=>"form-control"])}}
                <span class="text-danger">{{$errors->first('profile_type')}}</span>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h4>Billing information</h4>

            <div class="form-group">
                <label for="company">Company name:</label>
                {{Form::text('company',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>

            <span class="text-danger">{{$errors->first('company')}}</span>

            <div class="form-group">
                <label for="vat">VAT:</label>
                {{Form::text('vat',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>

            <span class="text-danger">{{$errors->first('vat')}}</span>

            <div class="form-group">
                <label for="cf">Fiscal Code:</label>
                {{Form::text('cf',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('cf')}}</span>

            <div class="form-group">
                <label for="billing_address">Billing address:</label>
                {{Form::text('billing_address',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('billing_address')}}</span>

            <div class="form-group">
                <label for="billing_city">City:</label>
                {{Form::text('billing_city',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('billing_city')}}</span>

            <div class="form-group">
                <label for="billing_address_zip">Address zip:</label>
                {{Form::text('billing_address_zip',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('billing_address_zip')}}</span>

            <div class="form-group">
                <label for="billing_country">Country:</label>
                {{Form::text('billing_country',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('billing_country')}}</span>

            <div class="form-group">
                <label for="billing_state">State:</label>
                {{Form::text('billing_state',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('billing_state')}}</span>

        </div>
        <div class="col-md-6">
            <h4>Send information</h4>

            <div class="form-group">
                <label for="shipping_address">Address:</label>
                {{Form::text('shipping_address',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('shipping_address')}}</span>

            <div class="form-group">
                <label for="shipping_city">City:</label>
                {{Form::text('shipping_city',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('shipping_city')}}</span>

            <div class="form-group">
                <label for="shipping_address_zip">Zip address:</label>
                {{Form::text('shipping_address_zip',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('shipping_address_zip')}}</span>

            <div class="form-group">
                <label for="shipping_country">Country:</label>
                {{Form::text('shipping_country',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('shipping_country')}}</span>

            <div class="form-group">
                <label for="shipping_state">State:</label>
                {{Form::text('shipping_state',null,['class'=>'form-control','autocomplete' => 'off'])}}
            </div>
            <span class="text-danger">{{$errors->first('shipping_state')}}</span>


        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            {{Form::hidden('user_id', $user_profile->user_id)}}
            {{Form::hidden('id', $user_profile->id)}}
            {{Form::submit('save',['class' =>'btn btn-primary margin-bottom-30'])}}
        </div>
    </div>
    {{Form::close()}}
</div>
@stop
