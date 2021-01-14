<?php

namespace App\Exports;

use App\Models\Survey;
use Ignite\Crud\Models\Repeatable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SurveyExport implements FromCollection, WithHeadings
{
    protected $results;

    public function __construct(Survey $survey)
    {
        $this->results = $survey->results()->pluck('results');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->results;
    }

    /**
     * Add a heading row
     *
     * @return array
     */
    public function headings(): array
    {
        $keys = collect($this->results->first())->keys()->all();
        
        $headings = collect($keys)->map(function ($key) {
            return Repeatable::find(explode('-', $key)[1])->question;
        });

        return $headings->toArray();
    }
}
