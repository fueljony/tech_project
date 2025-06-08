<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = [
        'string_value', 'date_value', 'json_value', 'survey_question_id'
    ];

    protected $casts = [
        'json_value' => 'array',
    ];

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

}
