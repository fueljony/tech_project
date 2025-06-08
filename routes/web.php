<?php

use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\SurveysController;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\SurveysController@index');

Route::get('/students', function () {
    return view('students');
});

Route::resource('surveys', SurveysController::class);

Route::post('/survey/{survey}/add_question', [SurveyQuestionController::class, 'addQuestion'])->name('survey.add_question');

Route::put('/survey/{survey}/update_question/{survey_question}', [SurveyQuestionController::class, 'updateQuestion'])->name('survey.update_question');

Route::get('/survey/{survey}/questions', [SurveyQuestionController::class, 'getSurveyQuestions'])->name('survey.questions');

Route::delete('/survey/{survey}/question/{survey_question}', [SurveyQuestionController::class, 'deleteQuestion'])->name('survey.delete_question');

// todo: add delete route and function in controller
