<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use App\Exports\SurveyExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveyController
{
    public function show($slug)
    {
        $survey = Survey::whereHas('translation', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->with('questions')->first();
        
        return view('surveys.show')->with([
            'survey' => $survey
        ]);
    }

    public function storeResult(Request $request, Survey $survey)
    {
        $survey->load('questions');

        $this->validate($request, $survey);

        $survey->results()->create([
            'results' => $request->questions
        ]);
    }

    private function validate(Request $request, Survey $survey): void
    {
        foreach ($survey->questions as $question) {
            if ($question->validate) {
                $rules["id-$question->id"] = implode('|', $question->validate);
            }
        }
        $validator = Validator::make($request->questions, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }
}
