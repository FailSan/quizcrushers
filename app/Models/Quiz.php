<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'type'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tests() {
        return $this->hasMany(Test::class);
    }

    public function updateStatus() {
        $count = $this->tests->count();
        if($count == 9) {
            $lastTest = $this->tests->sortByDesc("created_at")->first();
            if($lastTest->hasAnswer()) {
                $this->closed = 1;
            }
        }

        if($this->save())
            return $this;
        else
            return false;
    }
}
