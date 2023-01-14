<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Quiz;
use App\Models\Test;


class QuizController extends Controller
{
    public function quizCenter($quiz_type) {
        $currentUser = Auth::user();
        $currentQuiz;

        $lastUserQuiz = $currentUser->quizzes->where("type", $quiz_type)->where("closed", 0);
        if($lastUserQuiz->isNotEmpty())
            $currentQuiz = $lastUserQuiz->first();
        else {
            $currentQuiz = Quiz::create([
                "user_id" => $currentUser->id,
                "type" => $quiz_type
            ]);
        }

        return redirect()->route("quizView", ["quiz_type" => $currentQuiz->type, "quiz_id" => $currentQuiz->id]);
    }

    public function quizView($quiz_type, $quiz_id) {
        $currentQuiz = Quiz::find($quiz_id);

        return view('quiz')->with("currentQuiz", $currentQuiz)->with("currentUser", $currentQuiz->user);
    }
}
