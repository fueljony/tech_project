@extends('app')
@section('header-scripts')
    @vite(['resources/js/app.js'])
@stop
@section('content')
    <div id="app">
        <app />
    </div>
@stop
{{-- list your surveys in a table here, add "edit" button to edit the survey, on survey name click to go survey
<pre>
    @php print_r($surveys->toArray()) @endphp
</pre> --}}
