<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SurveyAnswerController extends Controller
{
    public function store(Request $request, Survey $survey)
    {
        $validator = Validator::make($request->all(), [
            'answers' => ['required', 'array'],
            'answers.*' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->answers as $questionId => $answer) {
                // Verify that the question belongs to the survey
                $question = SurveyQuestion::where('survey_id', $survey->id)
                    ->where('id', $questionId)
                    ->firstOrFail();

                $answerData = [
                    'survey_question_id' => $questionId,
                    'string_value' => null,
                    'date_value' => null,
                    'json_value' => null
                ];
                switch ($question->value_type) {
                    case 'multiple_choice':
                    case 'multiple_answer':
                        $answerData['json_value'] = $answer;
                        break;
                    case 'input_text':
                        $answerData['string_value'] = $answer;
                        break;
                    case 'date_picker':
                        $answerData['date_value'] = $answer;
                        break;
                }



                SurveyAnswer::updateOrCreate(
                    ['survey_question_id' => $questionId],
                    $answerData
                );

            }

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Answered Recorded',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to submit survey answers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
