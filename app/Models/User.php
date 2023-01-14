<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;


use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'gender',
        'birthdate',
        'email',
        'username',
        'password',
        'degree_id',
        'degree_vote',
        'math_vote',
        'physics_vote',
        'faction_id',
        'avatar_id',
        'experience',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    protected $appends = ["correct_rate"];

    protected function getCorrectRateAttribute() {
        return $this->correctRate();
    }

    public function faction() {
        return $this->belongsTo(Faction::class);
    }

    public function avatar() {
        return $this->belongsTo(Avatar::class);
    }

    public function degree() {
        return $this->belongsTo(Degree::class);
    }

    public function tests() {
        return $this->hasMany(Test::class);
    }

    public function quizzes() {
        return $this->hasMany(Quiz::class);
    }

    public function challengesOut() {
        return $this->hasMany(Challenge::class, "from_user_id");
    }
    
    public function challengesIn() {
        return $this->hasMany(Challenge::class, "to_user_id");
    }

    public function powerCooldown($power_id) {
        $currentUser = $this;
        $tests = $currentUser->tests->sortByDesc("created_at");
        $currentCooldown = 0;

        foreach($tests as $test) {
            $powers = $test->powers()
                ->wherePivot("power_id", $power_id)
                ->orderByPivot("created_at", "desc")
                ->get();
            if($powers->isNotEmpty()) {
                $lastUse = Carbon::parse($powers->first()->pivot->created_at);
                $newUse = Carbon::parse($lastUse)->addHour($powers->first()->cooldown);
                
                $currentCooldown = Carbon::now()->diffInMinutes($newUse, false);
                break;
            } else {
                $currentCooldown = -1;
            }
        }

        return $currentCooldown;
    }

    public function startCondition() {
        $currentUser = $this;

        $userDegree = $currentUser->degree->current_difficulty;
        $userDegreeVote = $currentUser->degree_vote;
        $userMathVote = $currentUser->math_vote;
        $userPhysicsVote = $currentUser->physics_vote;
        $userStartDiff = $userDegree * (($userMathVote * $userPhysicsVote)/64) * ($userDegreeVote/100);

        return $userStartDiff;
    }

    public function correctRate() {
        $currentUser = $this;

        $userTests = $currentUser->tests;
        $userTestsCorrect = 0;
        if($userTests->isNotEmpty()) {
            foreach($userTests as $userTest) {
                if($userTest->hasAnswer() && $userTest->isCorrect()) {
                    $userTestsCorrect++;
                }
            }
            $userCorrectRate = round(($userTestsCorrect / $userTests->count()), 2);
            return $userCorrectRate;
        } else {
            return 1;
        }
    }

    public function addExp($exp) {
        $newExp = $this->experience + $exp;
        $this->experience = $newExp;

        //Update Sfide
        $challengesIn = $this->challengesIn->where("state", "running");
        $challengesOut = $this->challengesOut->where("state", "running");

        if($challengesIn->isNotEmpty()) {
            foreach($challengesIn as $challengeIn) {
                    $challengeIn->to_user_exp = $challengeIn->to_user_exp + $exp;
                    $challengeIn->save();
            }
        }
        if($challengesOut->isNotEmpty()) {
            foreach($challengesOut as $challengeOut) {
                    $challengeOut->from_user_exp = $challengeOut->from_user_exp + $exp;
                    $challengeOut->save();
            }
        }

        //Update Livello
        if($newExp > ($this->level*5000)) {
            $this->level++;
        }

        if($this->save())
            return $this;
        else
            return false;
    }
}
