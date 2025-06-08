<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyAnswerController extends Controller
{
    /**
     * Store survey answers for a given survey
     * 
     * @param Survey $survey The survey being answered
     * @param Request $request The request containing answers
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Survey $survey, Request $request)
    {
        // Get all questions for this survey to validate against
        $questions = $survey->questions;
        
        // Build validation rules based on question types
        $rules = [];
        foreach ($questions as $question) {
            $rule = 'required';
            
            switch ($question->value_type) {
                case 'multiple_answer':
                    $rule .= '|array|min:1';
                    break;
                case 'date_picker':
                    $rule .= '|date';
                    break;
                case 'input_text':
                    $rule .= '|string|max:1000';
                    break;
                case 'multiple_choice':
                    $rule .= '|string|in:' . implode(',', $question->options);
                    break;
            }
            
            $rules["answers.{$question->id}"] = $rule;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Store each answer
        foreach ($request->answers as $questionId => $value) {
            $question = $questions->firstWhere('id', $questionId);
            
            $answer = new SurveyAnswer();
            $answer->survey_id = $survey->id;
            $answer->survey_question_id = $questionId;
            
            // Store value in appropriate column based on type
            switch ($question->value_type) {
                case 'date_picker':
                    $answer->date_value = $value;
                    break;
                case 'multiple_answer':
                    $answer->json_value = $value;
                    break;
                default:
                    $answer->string_value = $value;
            }
            
            $answer->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Survey answers submitted successfully'
        ]);
    }
} 