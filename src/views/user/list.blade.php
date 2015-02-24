@extends('authentication::layouts.base-2cols')

@section('title')
Admin area: users list
@stop

@section('content')

<div class="row" style="margin-bottom:20px;">
    <div class="col-md-12">
        <div class="col-md-9">
            {{-- print messages --}}
            <?php $message = Session::get('message'); ?>
            @if( isset($message) )
            <div class="alert alert-success">{{$message}}</div>
            @endif
            {{-- print errors --}}
            @if($errors && ! $errors->isEmpty() )
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
            @endforeach
            @endif
            <h3>Utenti</h3>
            @if(! $users->isEmpty() )
            <table class="table table-striped">
                <tr>
                    <th>State</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th></th>
                </tr>
                @foreach($users as $user)
                <tr>
                    <td>
                        <span class="badge {{$user->activated ? 'badge-green' : 'badge-red'}}">&nbsp;</span>
                    </td>
                    <td>
                        {{$user->email}}
                    </td>
                    <td> {{($user->first_name) ? ucfirst($user->first_name): ''}} {{($user->last_name) ?
                        ucfirst($user->last_name): ''}}
                    </td>
                    <td class="width-name-user-list">
                        <div class="user-buttons pull-right">

                            @if($user->permissionToEdit)
                            @if(! $user->blocked)
                            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editProfile', ['user_id' => $user->id])}}"><span
                                    class="glyphicon glyphicon-user"></span> Profile</a>
                            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser', ['id' => $user->id])}}"><span
                                    class="glyphicon glyphicon-edit"></span> Edit</a>
                            <a class="delete" href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteUser',['id' => $user->id, '_token' => csrf_token()])}}"><span
                                    class="glyphicon glyphicon-trash"></span> Delete</a>
                            @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>

            {{-- pagination links --}}
            {{$users->links()}}

            @else
            <h5>No results found.</h5>
            @endif
            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@editUser')}}"
               class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add new</a>
        </div>
        <div class="col-md-3">
            @include('authentication::user.search')
        </div>
    </div>
</div>
@stop

@section('footer_scripts')
<script>
    $(".delete").click(function () {
        return confirm("Are you sure?");
    });
</script>
@stop