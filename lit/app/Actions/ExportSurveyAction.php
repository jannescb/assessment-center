<?php

namespace Lit\Actions;

use App\Models\Survey;
use App\Exports\SurveyExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExportSurveyAction
{
    /**
     * Run the action.
     *
     * @param  Collection  $models
     * @return JsonResponse
     */
    public function run(Collection $models)
    {
        $survey = $models->first();

        $filename = $survey->translations[0]->slug ?? 'survey';

        return Excel::download(new SurveyExport($survey), "$filename.xlsx");
    }
}
