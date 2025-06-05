@extends('app')
@section('header-scripts')
    @vite(['resources/js/app.js'])
@stop
@section('content')
    <div id="app">
        <app />
    </div>
@stop
{{-- show the survey to the respondent, extra credit if you can validate and store the answers --}}
