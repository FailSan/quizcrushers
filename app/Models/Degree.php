<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_difficulty',
        'current_difficulty'
    ];

    public $timestamps = false;

    public function users() {
        return $this->hasMany(User::class);
    }
}
