@if( App::make('authenticator')->getLoggedUser()->hasAccess("_super_admin") )
    <h3>Gruppi</h3>
    <h3>Gruppi associati:</h3>
    @if( ! $user->groups->isEmpty() )
    <ul class="list-group">
        @foreach($user->groups as $group)
        <li class="list-group-item">
            <span class="glyphicon glyphicon-user"></span> {{$group->name}}
            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteGroup',['_token' => csrf_token(), 'group_id' => $group->id, 'id' => $user->id])}}
            " ><span class="glyphicon glyphicon-trash pull-right margin-left-5 delete">cancella </span></a>
            <span class="clearfix"></span>
        </li>
        @endforeach
    </ul>
    @else
    <h5>Non ci sono gruppi associati all' utente.</h5>
    @if(! $user->exists)
        <div class="alert alert-danger">
          <h5>Per associare un gruppo bisogna prima creare l'utente.</h5>
        </div>
    @endif
    @endif

    {{-- form to associate groups --}}
    {{Form::open(["action" => "Palmabit\Authentication\Controllers\UserController@addGroup"])}}
    <div class="form-group">
        {{Form::label('group_id', 'Aggiungi gruppo:', ["class" => "control-label"])}}<br/>
        {{Form::select('group_id', $group_values, '', ["class"=>"form-control"])}}
        <span class="text-danger">{{$errors->first('name')}}</span>
        {{Form::hidden('id', $user->id)}}
    </div>
    <div class="form-group">
        {{Form::submit('Aggiungi', ["class" => "btn btn-primary", ($user->exists ) ? "" : "disabled"])}}
    </div>
@endif
{{Form::close()}}