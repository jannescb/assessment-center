<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResult extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['survey_id', 'results'];
    
    /**
     * The attributes that are cast.
     *
     * @var array
     */
    protected $casts = ['results' => 'json'];

    /**
     * A SurveyResult belongs to a Survey.
     *
     * @return BelongsTo
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}
