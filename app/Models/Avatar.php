<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'faction_id',
        'required_lvl',
        'fullsize_url',
        'minisize_url'
    ];

    public function faction() {
        return $this->belongsTo(Faction::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

}