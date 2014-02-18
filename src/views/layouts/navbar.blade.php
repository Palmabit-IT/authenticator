<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/" target="_blank">{{$app_name}}</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @if(isset($menu_items))
                    @foreach($menu_items as $item)
                        <li class=""> <a href="{{$item->getLink()}}">{{$item->getName()}}</a></li>
                    @endforeach
                @endif
            </ul>
            <div class="navbar-form navbar-right">
                <a href="{{URL::to('/user/logout')}}" class="btn btn-warning">Logout</a>
            </div>
        </div><!--/.nav-collapse -->
    </div>
</div>