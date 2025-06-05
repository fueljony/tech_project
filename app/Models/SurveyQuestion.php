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
        switch ($this->value_type) {
            case "multiple_choice":
                return $this->answer->string_value;
            break;
            case "date_picker":
                return $this->answer->date_value;
                break;
            case "input_text":
                return $this->answer->string_value;
                break;
            case "multiple_answer":
                return $this->answer->json_value;
                break;
        }
        return "";
    }
}
