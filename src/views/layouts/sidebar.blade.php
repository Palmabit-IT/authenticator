<ul class="nav nav-pills nav-stacked">
    @if(isset($sidebar_items) && $sidebar_items)
    @foreach($sidebar_items as $name => $link)
    <li class="{{Palmabit\Library\Views\Helper::get_active($link[0])}}"><a href="{{$link[0]}}">{{$link[1]}}
            {{$name}}</a></li>
    @endforeach
    @endif
</ul>
