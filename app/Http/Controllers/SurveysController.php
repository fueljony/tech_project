<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('surveys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:surveys',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }

        $survey = Survey::create($validator->validated());

        if ($request->expectsJson()) {
            return new JsonResponse($survey, 201);
        }

        return redirect()->route('surveys.edit', $survey->id)
            ->with('success', 'Survey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $survey = Survey::with('questions')->findOrFail($id);

        if (request()->expectsJson()) {
            return new JsonResponse($survey);
        }

        return view('surveys.respondent', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $survey = Survey::with('questions')->findOrFail($id);
        return view('surveys.edit', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $survey = Survey::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:surveys,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
            'settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }

        $survey->update($validator->validated());

        if ($request->expectsJson()) {
            return new JsonResponse($survey);
        }

        return redirect()->route('surveys.edit', $survey->id)
            ->with('success', 'Survey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();

        if (request()->expectsJson()) {
            return new JsonResponse(null, 204);
        }

        return redirect()->route('surveys.index')
            ->with('success', 'Survey deleted successfully.');
    }
}
