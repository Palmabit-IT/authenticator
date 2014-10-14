<div class="panel panel-default margin-top-20">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> Find user</h3>
    </div>
    <div class="panel-body">
        {{Form::open(['action' => 'Palmabit\Authentication\Controllers\UserController@getList','method' => 'get'])}}

        {{Form::label('email', 'Email: ')}}
        {{Form::text('email', Input::get('email'),['class' => 'form-control'])}}

        {{Form::label('first_name', 'First name: ')}}
        {{Form::text('first_name', Input::get('first_name'),['class' => 'form-control'])}}

        {{Form::label('last_name', 'Last name: ')}}
        {{Form::text('last_name', Input::get('last_name'),['class' => 'form-control'])}}

        {{Form::label('billing_address_zip', 'Billing Zip: ')}}
        {{Form::text('billing_address_zip', Input::get('billing_address_zip'),['class' => 'form-control'])}}

        {{Form::label('code', 'User code:')}}
        {{Form::text('code', Input::get('code'),['class' => 'form-control'])}}

        <div class="form-group">
            {{Form::label('activated', 'Active: ')}}
            {{Form::select('activated', ['' => '', 1 => 'Sì', 0 => 'No'], Input::get('activated',''), ["class" => "form-control"])}}
        </div>
        <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@getList')}}" class="btn btn-default">Clear</a>
        {{Form::submit('Search', ["class" => "btn btn-primary"])}}
        {{Form::close()}}
    </div>
</div>