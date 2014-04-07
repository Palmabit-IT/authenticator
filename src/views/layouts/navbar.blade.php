<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">{{$app_name}}</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @if(isset($menu_items))
                    @foreach($menu_items as $item)
                        <li class="{{Palmabit\Library\Views\Helper::get_active_route_name($item->getRoute())}}"> <a href="{{$item->getLink()}}">{{$item->getName()}}</a></li>
                    @endforeach
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="glyphicon glyphicon-user"></i>{{App::make('authenticator')->getLoggedUser()->email}}<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{URL::to('/user/logout')}}" class="glyphicon glyphicon-log-out">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <p class="navbar-text navbar-right">
                <a href="{{URL::to('/')}}" style="margin-right:10px; color:#eee;" target="_blank">Vai a SFX shop</a>
            </p>
        </div><!--/.nav-collapse -->
    </div>
</div>