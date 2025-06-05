<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::orderBy("name","ASC")->get();

        return view('surveys.index', compact('surveys'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $survey = Survey::find($id);

        # here you need to show the survey to the respondent
        return view('surveys.respondent', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $survey = Survey::find($id);

        # here is where you need to add questions to the survey
        return view('surveys.respondent', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // extra credit (update name)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // extra credit (delete survey)
    }
}
