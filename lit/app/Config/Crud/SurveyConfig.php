<?php

namespace Lit\Config\Crud;

use App\Models\Survey;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\CrudShow;
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
        $page->card(function ($form) {
            $form->input('title');
            $form->input('email');
            $form->input('subject');
            $form->boolean('active');
        });
        $page->card(function ($form) {
            $form->block('questions')
                ->title('Questions')
                ->repeatables(function ($repeatables) {
                    $repeatables->add('Question', function ($form, $preview) {
                        $preview->col('{question}');

                        $form->input('question')
                            ->title('Question')->width(2/3);
                        
                        $form->checkboxes('validate')
                            ->options([
                                'email' => 'E-Mail',
                                'required' => 'Required',
                            ])
                            ->title('Validation')->width(1/3);

                        $form->select('question_type')
                            ->options([
                                'input' => 'Input',
                                'radio' => 'Radio',
                                'checkbox' => 'Checkbox',
                            ])
                            ->title('Type');

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
                            ->orWhen('question_type', 'checkbox');
                    });
                    $repeatables->add('Step', function ($form, $preview) {
                        $preview->col('Next Step');
                    });
                });
        });
    }
}
