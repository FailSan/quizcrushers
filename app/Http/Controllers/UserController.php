<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Challenge;

class UserController extends Controller
{
    public function userLogin(Request $request) {
        $credenziali;
        $remember_token = $request->remember_token;
        $validator = Validator::make($request->all(), [
            'user' => 'required|email',
            'password' => 'required'
        ]);
        
        if ($validator->fails()) {
            $validator = Validator::make($request->all(), [
                'user' => 'required|alpha',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            $credenziali = array('username' => $request->input('user'), 'password' => $request->input('password'));
        } else {
            $credenziali = array('email' => $request->input('user'), 'password' => $request->input('password'));
        }
        
        if (Auth::attempt($credenziali, $remember_token)) {
            return response()->json(["success" => "Credenziali Corrette"]);
        } else {
            return response()->json(["error" => "Credenziali Errate."]);
        }
    }

    public function userLogout() {
        Auth::logout();
        return redirect("/");
    }

    public function userCreation($validatedInputs) {
        $user = User::create([
            'gender' => $validatedInputs["gender"],
            'name' => $validatedInputs["name"],
            'surname' => $validatedInputs["surname"],
            'birthdate' => $validatedInputs["birthdate"],

            'username' => $validatedInputs["username"],
            'email' => $validatedInputs["email"],
            'password' => Hash::make($validatedInputs["password"]),

            'degree_id' => $validatedInputs["degree"],
            'degree_vote' => $validatedInputs["degree_vote"],
            'math_vote' => $validatedInputs["math_vote"],
            'physics_vote' => $validatedInputs["physics_vote"]
        ]);
    }

    public function userHome() {
        $currentUser = Auth::user();
        if(!$currentUser)
            return view("home");
        else
            return redirect()->route("dashboard");
    }

    public function userDashboard() {
        $currentUser = Auth::user();

        return view("dashboard")->with("currentUser", $currentUser);
    }

    public function setFaction($faction_id) {
        $currentUser = Auth::user();
        $currentUser->faction_id = $faction_id;

        if($currentUser->save())
            return response()->json(["success" => "Fazione settata correttamente."]);
        else
            return response()->json(["error" => "Fazione non settata correttamente."]);
    }

    public function setAvatar($avatar_id) {
        $currentUser = Auth::user();
        $currentUser->avatar_id = $avatar_id;

        if($currentUser->save())
            return response()->json(["success" => "Avatar settato correttamente."]);
        else
            return response()->json(["error" => "Avatar non settato correttamente."]);
    }

    public function getPowerCooldown($power_id) {
        $currentUser = Auth::user();
        $currentCooldown = $currentUser->powerCooldown($power_id);

        return response()->json(["power" => $power_id, "powerCooldown" => $currentCooldown]);
    }

    public function validateInput(Request $request) {
        $startDate = Carbon::now("Europe/Rome")->subYear(100);
        $finalDate = Carbon::now("Europe/Rome")->subYear(18);

        $validator = Validator::make($request->all(), [
            "gender" => "sometimes|required|alpha",
            "name" => "sometimes|required|alpha",
            "surname" => "sometimes|required|alpha",
            "birthdate" => "sometimes|required|date|after:".$startDate."|before:".$finalDate,

            "email" => "sometimes|required|email|unique:users",
            "username" => "sometimes|required|alpha_dash|unique:users|max:100",
            "password" => "sometimes|required|string|min:6|confirmed",
            
            "degree" => "sometimes|required|numeric",
            "degree_vote" => "sometimes|required|numeric|min:60|max:100",
            "math_vote" => "sometimes|required|numeric|min:0|max:10",
            "physics_vote" => "sometimes|required|numeric|min:0|max:10"
        ]);

        if($validator->fails())
            return response()->json(["error" => $validator->errors()]);
            
        else
            return response()->json(["success" => $validator->validated()]);
    }

    public function validateForm(Request $request) {
        $validator = $this->validateInput($request)->getOriginalContent();

        if(array_key_exists("error", $validator))
            return response()->json(["error" => $validator["error"]]);

        else {
            $validatedInputs = $validator["success"];
            $this->userCreation($validatedInputs);

            return response()->json(["success" => "Campi corretti. Utente registrato"]);
        }
    }

    public function rankUpdate($showFlag = "all", $sortFlag = "exp", $stringFlag = "") {
        $currentUser = Auth::user();
        $searchArray = [
            "currentUser" => $currentUser,
            "showFlag" => $showFlag,
            "sortFlag" => $sortFlag,
            "stringFlag" => $stringFlag
        ];
        $rankView = view("dashboard.rank", $searchArray)->render();
        
        return response()->json(["view" => $rankView]);
    }

    public function challengesUpdate() {
        $currentUser = Auth::user();

        return response()->json(["view" => view("dashboard.challenges", ["currentUser" => $currentUser])->render()]);
    }

    public function userProfile($user_id) {
        $currentOwner = User::find($user_id);
        $currentUser = Auth::user();

        return view("profile")->with("currentUser", $currentUser)->with("currentOwner", $currentOwner);
    }
}