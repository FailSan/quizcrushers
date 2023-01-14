<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ChallengeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UserController::class, 'userHome'])->name('home');
Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard')->middleware('auth', 'hasFaction', 'hasAvatar');
Route::post('/login', [UserController::class, 'userLogin']);
Route::get('/logout', [UserController::class, 'userLogout']);
Route::post('/validateInput', [UserController::class, 'validateInput']);
Route::post('/validateForm', [UserController::class, 'validateForm']);
Route::get('/user/power/{power_id}', [UserController::class, 'getPowerCooldown'])->middleware('auth');

Route::get('/faction/{faction_id}', [UserController::class, 'setFaction'])->middleware('auth');
Route::get('/avatar/{avatar_id}', [UserController::class, 'setAvatar'])->middleware('auth');

Route::get('/profile/{id}', [UserController::class, "userProfile"])->name('profile')->middleware('auth');

Route::get('/quiz/{quiz_type}', [QuizController::class, 'quizCenter'])->middleware('auth');
Route::get('/quiz/{quiz_type}/{quiz_id}', [QuizController::class, 'quizView'])->name('quizView')->middleware('auth');

Route::get("/test/{quiz_id}", [TestController::class, "testCenter"])->middleware("auth");
Route::get("/test/{quiz_id}/{test_id}", [TestController::class, "testView"])->name("testView")->middleware("auth");
Route::get("/test/{test_id}/power/{power_id}", [TestController::class, "powerCenter"])->middleware("auth");
Route::get("/test/{test_id}/option/{option_id}", [TestController::class, "optionCenter"])->middleware("auth");

Route::get('/challenge/{challenge_action}/{user_id}', [ChallengeController::class, 'challengeCenter'])->middleware('auth');
Route::get("/user/rank/{showFlag?}/{stringFlag?}/{sortFlag?}", [UserController::class, "rankUpdate"])->middleware("auth");
Route::get("/user/challenges/", [UserController::class, "challengesUpdate"])->middleware("auth");

/* Route::get('/populateQuestions', function() {
    $count = 1;
    for($i = 0; $i < 10; $i++) {
        for($j = 0; $j < 3; $j++) {
            $question = Question::create([
                "description" => "Quesito ".$count,
                "type" => "physics",
                "start_difficulty" => (($i+1)/10),
                "current_difficulty" => (($i+1)/10)
            ]);
            $count++;
        }
    }
    echo "Fatto.";
});
 */

/* Route::get('/populateOptions', function() {
    $questions = Question::all();
    
    for($i = 0; $i < $questions->count(); $i++) {
        $count = 1;
        for($j = 0; $j < 2; $j++) {
            $rightAnswer = Option::create([
                "question_id" => $questions[$i]->id,
                "description" => "Opzione ".$count,
                "correct" => 1
            ]);
            $count++;
        }
        for($k = 0; $k < 6; $k++) {
            $wrongAnswer = Option::create([
                "question_id" => $questions[$i]->id,
                "description" => "Opzione ".$count,
                "correct" => 0
            ]);
            $count++;
        }
    }

    echo "Fatto.";
}); */