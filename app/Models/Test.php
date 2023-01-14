<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'quiz_id',
        'required_time'
    ];

    protected $touches = ['quiz'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function options() {
        return $this->belongsToMany(Option::class, 'tests_options')
                    ->withPivot('choosen', 'visual_order')
                    ->orderByPivot('visual_order');
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
 
    public function powers() {
        return $this->belongsToMany(Power::class, 'tests_powers')->withPivot('created_at');
    }

    public function hasAnswer() {
        if($this->options()->wherePivot("choosen", 1)->exists())
            return true;
        else
            return false;
    }

    public function getAnswer() {
        if($this->hasAnswer())
            return $this->options()->wherePivot("choosen", 1)->first();
        else
            return false;
    }

    public function isCorrect() {
        if($this->options()->wherePivot("choosen", "1")->first()->correct)
            return true;
        else
            return false;
    }

    public function updateTime() {
        if($this->powers->where("id", 3)->isNotEmpty()) {
            $time = 1;
        } else {
            $time = Carbon::parse($this->created_at)->diffInSeconds(Carbon::now(), false);
        }

        $this->required_time = $time;
        if($this->save())
            return $this->required_time;
        else
            return false;
    }

    public function updateExp($time, $correct) {
        $baseExp = $this->question->current_difficulty * 1000;

        if($correct) {
            switch($time) {
                case ($time > 1800):
                    $realExp = $baseExp - ($baseExp * 0.5);
                    break;
                case ($time > 600):
                    $realExp = $baseExp - ($baseExp * 0.2);
                    break;
                case ($time > 180):
                    $realExp = $baseExp - ($baseExp * 0.1);
                    break;
                default:
                    $realExp = $baseExp;
                    break;
            }
        } else {
            $realExp = $baseExp * 0.1;
        }

        $this->experience = $realExp;
        if($this->save())
            return $realExp;
        else
            return false;
    }

}
