<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    public function index(Survey $survey)
    {
        return response()->json([
            'questions' => $survey->questions
        ]);
    }

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
        $surveyQuestion->update($input);

        return response()->json([
            'success' => true,
            'msg' => 'Question Updated',
        ]);
    }

    public function deleteQuestion(Survey $survey, SurveyQuestion $survey_question)
    {
        if ($survey_question->survey_id !== $survey->id) {
            return response()->json([
                'success' => false,
                'msg' => 'Question does not belong to this survey'
            ], 403);
        }

        $survey_question->answer()->delete();

        $survey_question->delete();
        return response()->json([
            'success' => true,
            'msg' => 'Question deleted successfully',
        ]);
    }


}
