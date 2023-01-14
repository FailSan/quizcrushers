<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'description',
        'type',
        'start_difficulty',
        'current_difficulty'
    ];

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function tips() {
        return $this->hasMany(Tip::class);
    }

    public function answers() {
        return $this->options->where('correct', 1)->get();
    }

    public function updateDiff($correctRate, $startCondition, $correct) {
        $currentDiff = $this->current_difficulty;

        if($correct) {
            $diffUp = ($correctRate + $startCondition) / 100;
            $newDiff = (($currentDiff + $diffUp) > 1) ? 1 : ($currentDiff + $diffUp);
        } else {
            $diffDown = ($correctRate + $startCondition) / 100;
            $newDiff = (($currentDiff - $diffDown) < 0.1) ? 0.1 : ($currentDiff - $diffDown);
        }

        $this->current_difficulty = $newDiff;
        if($this->save())
            return $this;
        else
            return false;
    }
}
