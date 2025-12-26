<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'question', 'value_type', 'options'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answer()
    {
        return $this->hasOne(SurveyAnswer::class);
    }

    public function getResponseAttribute()
    {
        return match($this->value_type) {
            "multiple_choice" => $this->answer->string_value,
            "date_picker" => $this->answer->date_value,
            "input_text" => $this->answer->string_value,
            "multiple_answer" => $this->answer->json_value,
            default => ""
        };
    }
}
