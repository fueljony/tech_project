<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    public function addQuestion(Survey $survey, Request $request)
    {
        $input = $request->only(['question', 'value_type', 'options']);
        // option should be an array
        $survey->questions()->create($input);

        return response()->json([
            'success' => true,
            'msg' => 'Question Added',
        ]);
    }

    public function updateQuestion(Survey $survey, SurveyQuestion $surveyQuestion, Request $request)
    {
        $input = $request->only(['question', 'value_type', 'options']);
        // option should be an array
        $surveyQuestion->update($input);

        return response()->json([
            'success' => true,
            'msg' => 'Question Updated',
        ]);
    }

}
