@php
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Profilo')</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('styles/private/inside.css') }}">
        <link rel="stylesheet" href="{{ asset('styles/private/profile.css') }}">
        
        <!-- Script -->
        <script src="{{ asset('javascript/private/profile.js') }}" defer></script>

    </head>

    <body @class(["light" => $currentOwner->faction_id == "1", "dark" => $currentUser->faction_id == "2"])>
        <div class="dialogBox hidden"></div>
        
        <header>
            <p><a href="/dashboard">Quiz Crushers</a></p>
            <navbar>
                <a href="/profile/{{ $currentUser->id }}">Profilo</a>
                <a href="/logout">Logout</a>
            </navbar>
        </header>
        
        <main>
            <div class="leftFrame">@include("profile.quiz")</div>

            <div class="midFrame">
                @php
                    $userLevel = $currentOwner->level;
                    $userExp = $currentOwner->experience;

                    $percentage = ($userExp / ($userLevel * 5000)) * 100;

                @endphp

                <div class="userContainer">                    
                    <div class="userExperience">
                        <svg viewBox="0 0 100 100">
                            <circle class="fullCircle"></circle>
                            <circle style="--percentage: calc(({{ $percentage }}% - 6px) * 3.14159);" class="percentageCircle"></circle>
                        </svg>
                    </div>
                    
                    <img class="userAvatar" src="{{ Storage::url('images/avatars/'.$currentOwner->avatar->fullsize_url) }}">

                    <div class="userInfo">
                        <div class="userInfoLabels">
                            <span>Username:</span>
                            <span>Livello:</span>
                            <span>Esperienza:</span>
                            <span>Nome:</span>
                            <span>Cognome:</span>
                        </div>
                            
                        <div class="userInfoValues">
                            <span>{{ $currentOwner->username }}</span>
                            <span>{{ $currentOwner->level }}</span>
                            <span>{{ $currentOwner->experience }} / {{ ($currentOwner->level * 5000) }}</span>
                            <span>{{ $currentOwner->name }}</span>
                            <span>{{ $currentOwner->surname }}</span>
                        </div>
                    </div>
                    
                    @if($currentUser->id == $currentOwner->id)
                        Modifica Dati
                    @endif
                </div>

                <div class="overviewFrame">
                    Qui Vanno i cerchi
                </div>
            </div>

            <div class="rightFrame">@include("profile.challenge")</div>
        </main>
        
        <footer>
            <p>Footer</p>
        </footer>
    </body>
</html>