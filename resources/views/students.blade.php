@extends('app')
@section('header-scripts')
    @vite(['resources/js/app.js'])
@stop
@section('content')
    <div id="app">
        <app />
    </div>
@stop
