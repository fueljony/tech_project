<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller for handling survey answer submissions
 * 
 * This controller manages the creation and storage of survey responses.
 * It handles validation of answers based on question types and ensures
 * data integrity through proper error handling and validation.
 */
class SurveyAnswerController extends Controller
{
    /**
     * Store survey answers for a given survey
     * 
     * @param Survey $survey The survey being answered
     * @param Request $request The request containing answers
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Survey $survey, Request $request)
    {
        // Check if survey is active and available for submission
        // if ($survey->status !== 'active') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'This survey is not currently accepting responses'
        //     ], 403);
        // }

        // Get all questions for this survey to validate against
        $questions = $survey->questions;
        
        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'This survey has no questions'
            ], 422);
        }
        
        // Build validation rules based on question types
        $rules = [];
        foreach ($questions as $question) {
            $rule = $question->required ? 'required' : 'nullable';
            
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

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'This question is required',
            'array' => 'Please select at least one option',
            'min' => 'Please select at least one option',
            'date' => 'Please enter a valid date',
            'string' => 'Please enter a valid response',
            'max' => 'Your response is too long',
            'in' => 'Please select a valid option'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \DB::beginTransaction();

            // Store each answer
            foreach ($request->answers as $questionId => $value) {
                $question = $questions->firstWhere('id', $questionId);
                
                if (!$question) {
                    throw new \Exception("Invalid question ID: {$questionId}");
                }
                
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

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Survey answers submitted successfully'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save survey answers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 