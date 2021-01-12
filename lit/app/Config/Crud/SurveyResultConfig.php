<?php

namespace Lit\Config\Crud;

use App\Models\SurveyResult;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\CrudShow;
use Lit\Http\Controllers\Crud\SurveyResultController;

class SurveyResultConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = SurveyResult::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = SurveyResultController::class;

    /**
     * Model singular and plural name.
     *
     * @param SurveyResult|null surveyResult
     * @return array
     */
    public function names(SurveyResult $surveyResult = null)
    {
        return [
            'singular' => 'SurveyResult',
            'plural'   => 'SurveyResults',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'survey-results';
    }

    /**
     * Build index page.
     *
     * @param  \Ignite\Crud\CrudIndex $page
     * @return void
     */
    public function index(CrudIndex $page)
    {
        $page->table(function ($table) {
            $table->col('Title')->value('{title}')->sortBy('title');
        })->search('title');
    }

    /**
     * Setup show page.
     *
     * @param  \Ignite\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('title');
        });
    }
}
