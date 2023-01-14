<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'description',
        'correct'
    ];

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function tests() {
        return $this->belongsToMany(Test::class, 'tests_options')->withPivot('choosen', 'visual_order');
    }

}
