<ul class="nav nav-pills nav-stacked">
@if(isset($sidebar) && $sidebar )
@foreach($sidebar as $voce => $link)
    <li class="{{get_active($link)}}"><a href="{{$link}}">{{$voce}}</a></li>
@endforeach
@endif
</ul>
