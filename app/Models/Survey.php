<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'name'
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

}
