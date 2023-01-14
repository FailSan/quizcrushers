@php
    $userLevel = $currentUser->level;
    $userExp = $currentUser->experience;

    $percentage = ($userExp / ($userLevel * 5000)) * 100;

@endphp

<div class="userContainer">                    
    <div class="userExperience">
        <svg viewBox="0 0 100 100">
            <circle class="fullCircle"></circle>
            <circle style="--percentage: calc(({{ $percentage }}% - 6px) * 3.14159);" class="percentageCircle"></circle>
        </svg>
    </div>
    
    <img class="userAvatar" src="{{ Storage::url('images/avatars/'.$currentUser->avatar->fullsize_url) }}">

    <div class="userInfo">
        <div class="userInfoLabels">
            <span>Username:</span>
            <span>Livello:</span>
            <span>Esperienza:</span>
            <span>Nome:</span>
            <span>Cognome:</span>
        </div>
            
        <div class="userInfoValues">
            <span>{{ $currentUser->username }}</span>
            <span>{{ $currentUser->level }}</span>
            <span>{{ $currentUser->experience }} / {{ ($currentUser->level * 5000) }}</span>
            <span>{{ $currentUser->name }}</span>
            <span>{{ $currentUser->surname }}</span>
        </div>
    </div>
</div>