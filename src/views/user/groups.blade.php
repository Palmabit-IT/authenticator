<h3>Groups associated:</h3>
<div class="row">
    <div class="form-select">
        {{-- form to associate groups --}}
        {{Form::open(["action" => "Palmabit\Authentication\Controllers\UserController@addGroup"])}}
        <div class="form-group">
            {{Form::label('group_id', 'Aggiungi gruppo:', ["class" => "control-label"])}}<br/>
            {{Form::select('group_id', $group_values, '', ["class"=>"form-control"])}}
        </div>
        <div class="form-group">
            <span class="text-danger">{{$errors->first('name')}}</span>
            {{Form::hidden('id', $user->id)}}
        </div>
        <div class="form-group">
            {{Form::submit('Add group', ["class" => "btn btn-primary margin-top-10 pull-right", ($user->exists ) ? "" :
            "disabled"])}}
        </div>
        {{Form::close()}}

    </div>
</div>

@if( ! $user->groups->isEmpty() )
<div class="row">
    <ul class="list-group margin-top-10">
        @foreach($user->groups as $group)
        <li class="list-group-item">
            <span class="glyphicon glyphicon-list-alt"></span> {{$group->name}}
            <a href="{{URL::action('Palmabit\Authentication\Controllers\UserController@deleteGroup',['_token' => csrf_token(), 'group_id' => $group->id, 'id' => $user->id])}}">
                <span class="glyphicon glyphicon-trash pull-right margin-left-5 delete "> delete </span>
            </a>
            <span class="clearfix"></span>
        </li>
        @endforeach
        @else
        <li class="list-group-item"><i>There are no groups associated to the user.</i></li>
    </ul>
</div>
@endif
