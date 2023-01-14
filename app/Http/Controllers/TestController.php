<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Models\Quiz;
use App\Models\Test;
use App\Models\Question;
use Carbon\Carbon;

class TestController extends Controller
{
    public function testCenter($quiz_id) {
        $currentQuiz = Quiz::find($quiz_id);
        $currentUser = $currentQuiz->user;
        $currentQuizType = $currentQuiz->type;
        if($currentQuizType == "easy" || $currentQuizType == "medium" || $currentQuizType == "hard") {
            $currentTestSetup = $this->testDiffSetup($currentQuiz);
        } else {
            $userStartCondition = $currentUser->startCondition();
            $userCorrectRate = $currentUser->correctRate();

            if($currentQuiz->tests->isEmpty()) {
                $startDifficulty = $userCorrectRate * $userStartCondition;
            } else {
                foreach($currentQuiz->tests->sortByDesc("created_at") as $test) {
                    if($test->hasAnswer()) {
                        $lastQuizTest = $test;
                        break;
                    }
                }
                $lastTestAnswer = $lastQuizTest->options()->wherePivot("choosen", 1)->first();
                if($lastTestAnswer->correct == 1) {
                    $newDifficulty = ($userCorrectRate <= 0.5) ? ($lastTestAnswer->question->current_difficulty + 0.05) : ($lastTestAnswer->question->current_difficulty + 0.1) ;
                    $startDifficulty = ($newDifficulty < 1) ? $newDifficulty : 1;
                } else {
                    $startDifficulty = $lastQuizTest->question->current_difficulty;
                }
            }
            $currentTestSetup = $this->testSetup($currentQuiz, $startDifficulty);
        }

        $currentQuestion = $currentTestSetup["currentQuestion"];
        $currentOptions = $currentTestSetup["currentOptions"];

        $currentTest = Test::create([
            "user_id" => $currentQuiz->user->id,
            "quiz_id" => $currentQuiz->id,
            "question_id" => $currentQuestion->id
        ]);
        $i = 1;
        foreach($currentOptions as $option) {
            $currentTest->options()->attach($option, ["visual_order" => $i]);
            $i++;
        }

        return redirect()->route("testView", ["quiz_id" => $currentQuiz->id, "test_id" => $currentTest->id]);
    }

    public function testDiffSetup($currentQuiz) {
        $currentDifficulty = $currentQuiz->type;
        switch($currentDifficulty) {
            case "easy":
                $inferiorLimit = 0.1;
                $superiorLimit = 0.4;
                break;
            case "medium":
                $inferiorLimit = 0.4;
                $superiorLimit = 0.7;
                break;
            case "hard":
                $inferiorLimit = 0.7;
                $superiorLimit = 1;
                break;
            default:
                return response()->json(["error" => "Non è stato possibile riconoscere la difficoltà."]);
                break;
        }

        $mathCount = 0;
        $physicsCount = 0;
        $logicCount = 0;
        
        $currentTests = $currentQuiz->tests;
        for($i = 0; $i < $currentTests->count(); $i++) {
            switch($currentTests[$i]->question->type) {
                case "math":
                    $mathCount++;
                    break;
                case "physics":
                    $physicsCount++;
                    break;
                case "logic":
                    $logicCount++;
                    break;
                default:
                    return response()->json(["error" => "Non è stato possibile riconoscere il tipo del Quesito."]);
            }
        }
        $whereTypes = [];
        if($mathCount < 3)
            $whereTypes[0] = "math";
        if($physicsCount < 3)
            $whereTypes[1] = "physics";
        if($logicCount < 3)
            $whereTypes[2] = "logic";
        
        $currentQuestions = $currentTests->pluck("question_id");
        $possibleQuestions = Question::all()->whereIn("type", $whereTypes)
            ->where("current_difficulty", ">=", $inferiorLimit)
            ->where("current_difficulty", "<=", $superiorLimit)
            ->whereNotIn("id", $currentQuestions);
        
        $currentQuestion = $possibleQuestions->random();

        $wrongOptions = $currentQuestion->options->where("correct", "0")->random(3);
        $rightOption = $currentQuestion->options->where("correct", "1")->random();

        $currentOptions = collect($wrongOptions);
        $currentOptions->push($rightOption);
        $currentOptions = $currentOptions->shuffle();

        $currentTestSetup = ["currentQuestion" => $currentQuestion, "currentOptions" => $currentOptions];

        return $currentTestSetup;
    }

    public function testSetup($currentQuiz, $startDifficulty) {
        $currentQuizType = $currentQuiz->type;
        $currentQuestions = $currentQuiz->tests->pluck("question_id");

        $inferiorLimit = $startDifficulty;
        $superiorLimit = $startDifficulty + 0.1;

        $possibleQuestions;

        $flag = 0;
        while($flag == 0) {
            $possibleQuestions = Question::all()->where('type', $currentQuizType)
                ->where('current_difficulty', '>=', $inferiorLimit)
                ->where('current_difficulty', '<=', $superiorLimit)
                ->whereNotIn("id", $currentQuestions);
            
            if($possibleQuestions->isEmpty()) {
                $inferiorLimit = (($inferiorLimit - 0.1) < 0.1) ? 0.1 : ($inferiorLimit - 0.1);
                $superiorLimit = (($superiorLimit + 0.1) > 1) ? 1 : ($superiorLimit + 0.1);
            } else {
                $flag = 1;
            }
        }

        $currentQuestion = $possibleQuestions->random();

        $wrongOptions = $currentQuestion->options->where("correct", "0")->random(3);
        $rightOption = $currentQuestion->options->where("correct", "1")->random();

        $currentOptions = collect($wrongOptions);
        $currentOptions->push($rightOption);
        $currentOptions = $currentOptions->shuffle();

        $currentTestSetup = ["currentQuestion" => $currentQuestion, "currentOptions" => $currentOptions];

        return $currentTestSetup;
    }

    public function testView($quiz_id, $test_id) {
        $currentTest = Test::find($test_id);

        return view("test")->with("currentTest", $currentTest)->with("currentUser", $currentTest->user);
    }

    public function optionCenter($test_id, $option_id) {
        //Update Pivot
        $currentTest = Test::find($test_id);
        if($currentTest->hasAnswer()) {
            return response()->json(["error" => "Hai già risposto al Test."]);
        }
        $currentTest->options()->updateExistingPivot($option_id, ["choosen" => "1"]);
        
        //Update Test
        $testRequiredTime = $currentTest->updateTime();
        if(!$testRequiredTime) {
            return response()->json(["error" => "Non è stato possibile aggiornare il Test."]);
        }
        $testCorrect = $currentTest->isCorrect();
        $testExp = $currentTest->updateExp($testRequiredTime, $testCorrect);
        if(!$testExp) {
            return response()->json(["error" => "Non è stato possibile aggiornare il Test."]);
        }
        
        //Update User
        $currentUser = $currentTest->user->addExp($testExp);
        if(!$currentUser) {
            return response()->json(["error" => "Non è stato possibile aggiornare l'Utente."]);
        }

        //Update Question
        $currentUserCR = $currentUser->correctRate();
        $currentUserSC = $currentUser->startCondition();
        $currentQuestion = $currentTest->question->updateDiff($currentUserCR, $currentUserSC, $testCorrect);
        if(!$currentQuestion) {
            return response()->json(["error" => "Non è stato possibile aggiornare il Quesito."]);
        }
        
        //Update Quiz
        $currentQuiz = $currentTest->quiz->updateStatus();
        if(!$currentQuiz) {
            return response()->json(["error" => "Non è stato possibile aggiornare il Quiz."]);
        }

        return response()->json(["success" => $currentTest->options]);
    }

    public function powerCenter($test_id, $power_id) {
        $currentTest = Test::find($test_id);
        $currentUser = $currentTest->user;
        $currentTestPower = $currentTest->powers->where("id", $power_id);
        if($currentTestPower->isNotEmpty()) {
            return response()->json(["error" => "Hai già usato questo Potere sul Test."]);
        } elseif($currentUser->powerCooldown($power_id) > 0) {
            return response()->json(["error" => "Il potere è in ricarica."]);
        } else {
            switch($power_id) {
                case 1:
                    return $this->useChangePower($test_id);
                case 2:
                    return $this->usePercentagePower($test_id);
                case 3:
                    return $this->useStopwatchPower($test_id);
                case 4:
                    return $this->useGuillotinePower($test_id);
                default;
                    return response()->json(["error" => "Il potere non è stato riconosciuto."]);
                    break;
            }
        }
    }

    public function useChangePower($test_id) {
        $currentTest = Test::find($test_id);
        $currentQuiz = $currentTest->quiz;
        $currenUser = $currentTest->user;
        if($currenUser->level < 3) {
            return response()->json(["error" => "Non sei di livello abbastanza alto per usare il potere."]);
        }
        
        $currentQuestionDifficulty = $currentTest->question->current_difficulty;
        $newQuestionDifficulty = (($currentQuestionDifficulty - 0.2) < 0.1) ? 0.1 : ($currentQuestionDifficulty - 0.2); 

        $currentTest->question_id = null;
        $currentTest->options()->detach();

        $newTestSetup = $this->testSetup($currentQuiz, $newQuestionDifficulty);
        $newQuestion = $newTestSetup["currentQuestion"];
        $newOptions = $newTestSetup["currentOptions"];

        $currentTest->question_id = $newQuestion->id;
        if(!$currentTest->save()) {
            return response()->json(["error" => "Non è stato possibile usare il Potere."]);
        } else {
            $i = 1;
            foreach($newOptions as $option) {
                $currentTest->options()->attach($option, ["visual_order" => $i]);
                $i++;
            }
            $currentTest->powers()->attach(1);
            return response()->json(["power" => 1, "powerResult" => true]);
        }
    }

    public function usePercentagePower($test_id) {
        $currentTest = Test::find($test_id);
        $currenUser = $currentTest->user;
        if($currenUser->level < 6) {
            return response()->json(["error" => "Non sei di livello abbastanza alto per usare il potere."]);
        }

        $currentQuestion = $currentTest->question;
        $currentOptions = $currentTest->options->pluck("id");
        
        $possibleTests = Test::all()->where("question_id", $currentQuestion->id);
        $testCounter = 0;
        $optionCounters = [
            $currentOptions[0] => 0,
            $currentOptions[1] => 0,
            $currentOptions[2] => 0,
            $currentOptions[3] => 0
        ];

        foreach($possibleTests as $test) {
            if($test->hasAnswer()) {
                $testCounter++;
                $answer = $test->getAnswer();
                switch($answer->id) {
                    case $currentOptions[0]:
                        $optionCounters[$currentOptions[0]]++;
                        break;
                    case $currentOptions[1]:
                        $optionCounters[$currentOptions[1]]++;
                        break;
                    case $currentOptions[2]:
                        $optionCounters[$currentOptions[2]]++;
                        break;
                    case $currentOptions[3]:
                        $optionCounters[$currentOptions[3]]++;
                        break;
                    default:
                        break;
                }
            }
        }

        if($testCounter != 0) {
            foreach($optionCounters as $option => $counter) {
                $percentage = ($counter / $testCounter) * 100;
                $optionCounters[$option] = $percentage;
            }
        } else {
            return response()->json(["error" => "Nessuno ha ancora risposto a questo Quesito."]);
        }

        $currentTest->powers()->attach(2);

        return response()->json(["power" => 2, "powerResult" => $optionCounters]);
    }

    public function useStopwatchPower($test_id) {
        $currentTest = Test::find($test_id);
        $currenUser = $currentTest->user;
        if($currenUser->level < 3) {
            return response()->json(["error" => "Non sei di livello abbastanza alto per usare il potere."]);
        }

        $currentTest->powers()->attach(3);
        $time = $currentTest->updateTime();

        if($time == 1) {
            return response()->json(["power" => 3, "powerResult" => true]);
        } else {
            return response()->json(["error" => "Non è stato possibile usare il Potere."]);
        }
    }

    public function useGuillotinePower($test_id) {
        $currentTest = Test::find($test_id);
        $currenUser = $currentTest->user;
        if($currenUser->level < 6) {
            return response()->json(["error" => "Non sei di livello abbastanza alto per usare il potere."]);
        }

        $currentOptions = $currentTest->options;

        $wrongOptions = $currentOptions->where("correct", 0)->random(2)->pluck("id");
        if($wrongOptions->isEmpty()) {
            return response()->json(["error" => "Non è stato possibile usare il Potere."]);
        } else {
            $currentTest->powers()->attach(4);
            return response()->json(["power" => 4, "powerResult" => $wrongOptions]);
        }
    }
}
