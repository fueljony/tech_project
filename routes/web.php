<?php

use Illuminate\Support\Facades\Route;

Route::get('/surveys', function () {
    return view('surveys');
});

Route::get('/students', function () {
    return view('students');
});
