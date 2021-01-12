<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

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
}
