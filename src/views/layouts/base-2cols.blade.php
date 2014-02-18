@extends('authentication::layouts.base')

@section('container')
    <div class="col-md-2 nav bs-sidenav">
        @include('admin.layouts.sidebar')
    </div>
    <div class="col-md-10">
        {{-- select per le lingue --}}
        @include('admin.layouts.lingua')
        @yield('content')
    </div>
@stop