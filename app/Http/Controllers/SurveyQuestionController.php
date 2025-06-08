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
            'questions' => $survey->questions()->get()
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
            'questions' => $survey->questions()->get()
        ]);
    }

    public function getSurveyQuestions(Survey $survey)
    {
        $questions = $survey->questions()->get();
        return response()->json([
            'success' => true,
            'questions' => $questions,
        ]);
    }

    public function deleteQuestion(Survey $survey, SurveyQuestion $surveyQuestion)
    {
        // Verify the question belongs to the survey
        if ($surveyQuestion->survey_id !== $survey->id) {
            return response()->json([
                'success' => false,
                'msg' => 'Question does not belong to this survey'
            ], 403);
        }

        $surveyQuestion->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Question deleted',
            'questions' => $survey->questions()->get()
        ]);
    }
}
