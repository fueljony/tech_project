<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!request()->expectsJson()) {
            return view('surveys.index');
        }
        $surveys = Survey::orderBy("name", "ASC")->get();
        $data = [
            'surveys' => $surveys,
        ];
        return new JsonResponse($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $survey = Survey::with('questions')->find($id);

        if (!request()->expectsJson()) {
            return view('surveys.respondent', compact('survey'));
        }

        return new JsonResponse([
            'survey' => $survey
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $survey = Survey::find($id);

        # here is where you need to add questions to the survey
        return view('surveys.edit', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $survey = Survey::findOrFail($id);
        $survey->name = $request->name;
        $survey->save();

        return response()->json([
            'message' => 'Survey updated successfully',
            'survey' => $survey
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $survey = Survey::findOrFail($id);

        foreach ($survey->questions as $question) {
            $question->answer()->delete();
        }

        $survey->questions()->delete();
        $survey->delete();

        return response()->json([
            'message' => 'Survey deleted successfully'
        ], 200);
    }
}
