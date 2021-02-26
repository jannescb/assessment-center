<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Ignite\Crud\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model implements TranslatableContract
{
    use Translatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'email', 'subject', 'active'];

    /**
     * The attributes to be translated.
     *
     * @var array
     */
    public $translatedAttributes = ['title', 'subject'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['translations'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['active' => 'boolean'];

    /**
     * A Survey has many SurveyResults.
     *
     * @return HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(SurveyResult::class);
    }

    // public function questions()
    // {
    //     return $this->repeatables('questions');
    // }
    public function steps()
    {
        return $this->repeatables('steps');
    }
}
