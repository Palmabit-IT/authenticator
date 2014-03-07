<ul class="nav nav-pills nav-stacked">
@if(isset($sidebar_items) && $sidebar_items)
@foreach($sidebar_items as $name => $link)
	<?php dd($sidebar_items) ?>
    <li class="{{Palmabit\Library\Views\Helper::get_active($link[0])}}">{{$link[1]}} <a href="{{$link}}">{{$name}}</a></li>
@endforeach
@endif
</ul>
