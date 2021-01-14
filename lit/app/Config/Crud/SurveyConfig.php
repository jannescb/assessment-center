<?php

namespace Lit\Config\Crud;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\CrudShow;
use Lit\Actions\ExportSurveyAction;
use Lit\Http\Controllers\Crud\SurveyController;

class SurveyConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Survey::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = SurveyController::class;

    /**
     * Model singular and plural name.
     *
     * @param Survey|null survey
     * @return array
     */
    public function names(Survey $survey = null)
    {
        return [
            'singular' => 'Survey',
            'plural'   => 'Surveys',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'surveys';
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
        $page->headerRight()->action('Exportieren', ExportSurveyAction::class);

        $page->card(function ($form) {
            $form->input('title')->width(10);
            $form->boolean('active')->width(2);
            $form->input('email')->width(5);
            $form->input('subject')->width(7);
        })->width(8);
        
        $page->card(function ($form) {
            $form->block('questions')
                ->title('Questions')
                ->repeatables(function ($repeatables) {
                    $repeatables->add('Question', function ($form, $preview) {
                        $preview->col('{question}');

                        $form->input('question')
                            ->title('Question');

                        $form->select('question_type')
                            ->options([
                                'input' => 'Input',
                                'radio' => 'Radio',
                                'checkbox' => 'Checkbox',
                                'select' => 'Select',
                            ])
                            ->title('Type')->width(6);
                        
                        $form->checkboxes('validate')
                            ->options([
                                'email' => 'E-Mail',
                                'required' => 'Required',
                            ])
                            ->title('Validation')->width(6);
                        
                        
                        $form->block('answers')
                            ->title('Answers')
                            ->repeatables(function ($repeatables) {
                                $repeatables->add('Answer', function ($form, $preview) {
                                    $preview->col('{answer}');
            
                                    $form->input('answer')
                                        ->title('Answer');
                                });
                            })
                            ->when('question_type', 'radio')
                            ->orWhen('question_type', 'select')
                            ->orWhen('question_type', 'checkbox');
                    });
                });
        })->width(12);
    }
}
