<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function powers() {
        return $this->hasMany(Power::class);
    }

    public function avatars() {
        return $this->hasMany(Avatar::class);
    }

}
