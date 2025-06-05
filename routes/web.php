<?php

use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\SurveysController;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Controllers\SurveysController@index');

Route::resource('surveys', SurveysController::class);

Route::post('/survey/{survey}/add_question', [SurveyQuestionController::class, 'addQuestion'])->name('survey.add_question');

Route::put('/survey/{survey}/update_question/{survey_question}', [SurveyQuestionController::class, 'updateQuestion'])->name('survey.update_question');

// todo: add delete route and function in controller
