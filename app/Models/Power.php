<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Power extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'faction_id',
        'required_lvl',
        'name',
        'description',
        'cooldown'
    ];

    public function faction() {
        return $this->belongsTo(Faction::class);
    }

    public function tests() {
        return $this->belongsToMany(Test::class, 'tests_powers')->withPivot('created_at');
    }

}