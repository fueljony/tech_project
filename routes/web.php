<?php

use App\Http\Controllers\SurveyAnswerController;
use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\SurveysController;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\SurveysController@index');

Route::get('/students', function () {
    return view('students');
});

Route::resource('surveys', SurveysController::class);

Route::get('/survey/{survey}/questions', [SurveyQuestionController::class, 'index'])->name('survey.questions.index');
Route::put('/survey/{survey}/update_question/{survey_question}', [SurveyQuestionController::class, 'updateQuestion'])->name('survey.update_question');
Route::post('/survey/{survey}/add_question', [SurveyQuestionController::class, 'addQuestion'])->name('survey.add_question');
Route::delete('/survey/{survey}/delete_question/{survey_question}', [SurveyQuestionController::class, 'deleteQuestion'])->name('survey.delete_question');

Route::post('/survey/{survey}/answers', [SurveyAnswerController::class, 'store']);
