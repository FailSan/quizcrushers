<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        "from_user_id",
        "to_user_id",
        "state",
        "from_user_exp",
        "to_user_exp",
        "prize_exp",
        "expiring_at"
    ];

    public function fromUser() {
        return $this->belongsTo(User::class, "from_user_id");
    }

    public function toUser() {
        return $this->belongsTo(User::class, "to_user_id");
    }

    public function updateState() {
        $expiringAt = Carbon::parse($this->expiring_at);

        if(Carbon::now()->diffInSeconds($expiringAt, false) <= 0) {
            
            $this->state = "expired";
            $prize = (($this->from_user_exp) + ($this->to_user_exp));
            $this->prize_exp = $prize;

            if($this->from_user_exp > $this->to_user_exp) {
                $this->fromUser->addExp($prize);
            } else {
                $this->toUser->addExp($prize);
            }
        }

        return $this->save();
    }
}
