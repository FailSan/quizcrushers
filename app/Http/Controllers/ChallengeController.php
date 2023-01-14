<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Models\Challenge;

class ChallengeController extends Controller
{
    public function challengeCenter($challenge_action, $user_id) {

        $currentUser = Auth::user();

        switch($challenge_action) {
            case "create":
                $newChallenge = Challenge::create([
                    "from_user_id" => $currentUser->id,
                    "to_user_id" => $user_id,
                    "state" => "waiting",
                    "expiring_at" => Carbon::now()->addDay(2)
                ]);
                return response()->json(["success" => "Sfida Lanciata!"]);
                break;
            case "accept":
                $confirmedChallenge = Challenge::where("from_user_id", $user_id)
                    ->where("to_user_id", $currentUser->id)
                    ->where("state", "waiting")->first();
                $confirmedChallenge->state = "running";
                $confirmedChallenge->save();
                return response()->json(["success" => "Sfida Confermata!"]);
                break;
            case "refuse":
                $refusedChallenge = Challenge::where("from_user_id", $user_id)
                    ->where("to_user_id", $currentUser->id)
                    ->where("state", "waiting")->first();
                $refusedChallenge->delete();
                return response()->json(["success" => "Sfida Rifiutata!"]);
                break;
            case "delete":
                $deletedChallenge = Challenge::where("from_user_id", $currentUser->id)
                    ->where("to_user_id", $user_id)
                    ->where("state", "waiting")->first();
                $deletedChallenge->delete();
                return response()->json(["success" => "Sfida Annullata!"]);
                break;
            default:
                break;
        }
    }
}
