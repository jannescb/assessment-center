<?php

namespace Lit\Config\Crud;

use App\Models\Survey;
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
            'singular' => 'Umfrage',
            'plural'   => 'Umfragen',
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
        $page->navigationControls()->action('Exportieren', ExportSurveyAction::class);
        
        if ($this->getModelInstance()) {
            $page->preview(function ($model) {
                return route('surveys.show', [
                    'id' => $this->getModelInstance()->id
                ]);
            });
        }

        $page->info('Allgemein')
            ->text('Geben Sie die allgemeinen Informationen zur Umfrage. ')
            ->width(4);
        $page->card(function ($form) {
            $form->input('title')->width(10)->title('Name der Umfrage');
            $form->boolean('active')->width(2)->title('aktiv');
            $form->input('email')->width(6)->title('E-Mail')->hint('An wen sollen Umfrageergebnisse geschickt werden?');
            $form->input('subject')->width(6)->title('Betreff')->hint('Betreff für die E-Mail');
        })->width(8);

        $page->info('Fragen')
            ->text('Erstellen Sie hier die mehrstufige Umfrage. Jede Stufe kann beliebig viele Fragen enthalten')
            ->width(4);
        
        $page->card(function ($form) {
            $form->block('steps')
                ->title('Stufen')
                ->repeatables(function ($repeatables) {
                    $repeatables->add('step', function ($form, $preview) {
                        $preview->col('Stufe');
                        $form->block('questions')
                            ->title('Fragen')
                            ->repeatables(function ($repeatables) {
                                $repeatables->add('question', function ($form, $preview) {
                                    $preview->col('{question}');

                                    $form->input('question')
                                        ->title('Frage');

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
                                            'required' => 'Pflichtfeld',
                                        ])
                                        ->title('Validierung')->width(6);
                                    
                                    
                                    $form->listing('answers', function ($form) {
                                        $form->input('answer');
                                    })
                                        ->title('Antworten')
                                        ->when('question_type', 'radio')
                                        ->orWhen('question_type', 'select')
                                        ->orWhen('question_type', 'checkbox');
                                })->button('Frage hinzufügen');
                            });
                    })->button('Stufe hinzufügen');
                    ;
                });
        })->width(8);
    }
}
