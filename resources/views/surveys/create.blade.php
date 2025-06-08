@extends('app')
@section('header-scripts')
    @vite(['resources/sass/app/style.scss', 'resources/js/app.js'])
@stop
@section('content')
    <div id="app">
        <app />
    </div>
@stop 