@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Dashboard')</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('styles/private/inside.css') }}">
        <link rel="stylesheet" href="{{ asset('styles/private/dashboard.css') }}">
    
        <link rel="stylesheet" href="{{ asset('styles/private/dashboard/rankings.css') }}">
        <link rel="stylesheet" href="{{ asset('styles/private/dashboard/challenges.css') }}">
        @section("styles")
        @show
        
        <!-- Script -->
        <script src="{{ asset('javascript/private/dashboard.js') }}" defer></script>
        @section("scripts")
        @show

    </head>

    <body @class(["light" => $currentUser->faction_id == "1", "dark" => $currentUser->faction_id == "2"])>
        <div class="dialogBox hidden"></div>
        
        <header>
            <p><a href="/dashboard">Quiz Crushers</a></p>
            <navbar>
                <a href="/profile/{{ $currentUser->id }}">Profilo</a>
                <a href="/logout">Logout</a>
            </navbar>
        </header>
        
        <main>
            <div class="leftFrame">
            @section("user")
                @include("dashboard.user")
            @show
            @section("powers")
                @include("dashboard.powers")
            @show
            </div>
            <div class="midFrame">
            @section("content")
                @include("dashboard.quiz")
            @show
            </div>
            <div class="rightFrame">
                <div class="rankFrame">@include("dashboard.rank", ["showFlag" => "all", "stringFlag" => "", "sortFlag" => "exp"])</div>
                <div class="challengeFrame">@include("dashboard.challenges")</div>
            </div>
        </main>
        
        <footer>
            <p>Footer</p>
        </footer>
    </body>
</html>