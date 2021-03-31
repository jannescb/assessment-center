<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveyController
{
    public function show($id)
    {
        $survey = Survey::with('steps')->find($id);

        $data = collect([
            'id' => $survey->id,
            'title' => $survey->title,
            'steps' => $survey->steps->map(function ($step) {
                return $step->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question' => $question->question,
                        'type' => $question->question_type,
                        'answers' => $question->answers,
                        'validate' => $question->validate,
                    ];
                });
            })
        ]);
        
        return view('surveys.show')->with([
            'survey' => $data,
        ]);
    }

    public function storeResult(Request $request, Survey $survey)
    {
        $survey->load('steps');

        $this->validate($request, $survey);

        $survey->results()->create([
            'results' => $request->questions
        ]);
    }

    private function validate(Request $request, Survey $survey): void
    {
        $steps = $survey->steps->map(function ($step) {
            return $step->questions->mapWithKeys(function ($question) {
                return [
                    $question->id => $question->validate,
                ];
            });
        });

        foreach ($steps as $questions) {
            foreach ($questions as $id => $rulesArray) {
                if (is_array($rulesArray)) {
                    $rules["id-$id"] = implode('|', $rulesArray);
                }
            }
        }
        $validator = Validator::make($request->questions, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }
}
