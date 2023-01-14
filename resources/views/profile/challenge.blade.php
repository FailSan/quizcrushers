@php

    use Carbon\Carbon;
    use Carbon\CarbonInterface;

    $ownerChallengesIn = $currentOwner->challengesIn;
    $ownerChallengesOut = $currentOwner->challengesOut;

@endphp

<div class="challengeFrame">
    <div class="challengeType" data-challenge-sel="1">
        <div class="challengeTypeLabel">
            <p>Sfide in Corso</p>
            <span>
                <p>{{ $ownerChallengesIn->where("state", "running")->count() + $ownerChallengesOut->where("state", "running")->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="challengeCollection">
            
            @foreach($ownerChallengesIn->where("state", "running") as $challengeIn)
                @php
                    $totalExp = $challengeIn->from_user_exp + $challengeIn->to_user_exp;
                    if($totalExp == 0) {
                        $fromPercentage = 0;
                        $toPercentage = 0;
                    } else {
                        $fromPercentage = round((($challengeIn->from_user_exp / $totalExp) * 100), 2);
                        $toPercentage = round((($challengeIn->to_user_exp / $totalExp) * 100), 2);
                    }
                @endphp
                
                <span class="challengeContainer running">
                    <span class="fromUser" style="--percentage: {{ $fromPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$challengeIn->fromUser->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeIn->fromUser->username }}</p>
                            <p>{{ $challengeIn->from_user_exp }}</p>
                        </span>
                    </span>

                    <span class="toUser" style="--percentage: {{ $toPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$challengeIn->toUser->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeIn->to_user_exp }}</p>
                            <p>{{ $challengeIn->toUser->username }}</p>
                        </span>
                    </span>
                    @php
                        $expiring_at = Carbon::parse($challengeIn->expiring_at);
                        $expired = false;
                        if(Carbon::now()->diffInSeconds($expiring_at, false) <= 0) {
                            $expired = true;
                            $challengeIn->updateState();
                        }
                    @endphp
                    @if(!$expired)
                        <p class="challengeCooldown">Scade tra {{ $expiring_at->diffForHumans(Carbon::now(), CarbonInterface::DIFF_ABSOLUTE) }}</p>
                    @endif
                </span>
            @endforeach

            @foreach($ownerChallengesOut->where("state", "running") as $challengeOut)
                @php
                    $totalExp = $challengeOut->from_user_exp + $challengeOut->to_user_exp;
                    if($totalExp == 0) {
                        $fromPercentage = 0;
                        $toPercentage = 0;
                    } else {
                        $fromPercentage = round((($challengeOut->from_user_exp / $totalExp) * 100), 2);
                        $toPercentage = round((($challengeOut->to_user_exp / $totalExp) * 100), 2);
                    }
                @endphp
                
                <span class="challengeContainer running">
                    <span class="fromUser" style="--percentage: {{ $fromPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$challengeOut->fromUser->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeOut->fromUser->username }}</p>
                            <p>{{ $challengeOut->from_user_exp }}</p>
                        </span>
                    </span>

                    <span class="toUser" style="--percentage: {{ $toPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$challengeOut->toUser->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeOut->to_user_exp }}</p>
                            <p>{{ $challengeOut->toUser->username}}</p>
                        </span>
                    </span>
                    @php
                        $expiring_at = Carbon::parse($challengeOut->expiring_at);
                        $expired = false;
                        if(Carbon::now()->diffInSeconds($expiring_at, false) <= 0) {
                            $expired = true;
                            $challengeOut->updateState();
                        }
                    @endphp
                    @if(!$expired)
                        <p class="challengeCooldown">Scade tra {{ $expiring_at->diffForHumans(Carbon::now(), CarbonInterface::DIFF_ABSOLUTE) }}</p>
                    @endif
                </span>
            @endforeach
        </div>
    </div>
    
    @if($currentOwner->id == $currentUser->id)
    <div class="challengeType" data-quiz-sel="0">
        <div class="challengeTypeLabel">
            <p>Sfide in Attesa</p>
            <span>
                <p>{{ $ownerChallengesIn->where("state", "waiting")->count() + $ownerChallengesOut->where("state", "waiting")->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="challengeCollection">
            @foreach($ownerChallengesIn->where("state", "waiting") as $challengeIn)
                <span class="challengeContainer waiting">
                    <img src="{{ Storage::url('images/avatars/'.$challengeIn->fromUser->avatar->minisize_url) }}">
                    <p><strong>{{ $challengeIn->fromUser->username }}</strong></p>
                    <span>
                        <a data-user-id="{{ $challengeIn->fromUser->id }}" data-challenge-action="accept">Accetta</a>
                        <a data-user-id="{{ $challengeIn->fromUser->id }}" data-challenge-action="refuse">Rifiuta</a>
                    </span>
                </span>
            @endforeach

            @foreach($ownerChallengesOut->where("state", "waiting") as $challengeOut)
                <span class="challengeContainer waiting">
                    <img src="{{ Storage::url('images/avatars/'.$challengeOut->toUser->avatar->minisize_url) }}">
                    <p><strong>{{ $challengeOut->toUser->username }}</strong></p>
                    <span>
                        <a data-user-id="{{ $challengeOut->toUser->id }}" data-challenge-action="delete">Annulla</a>
                    </span>
                </span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="challengeType" data-quiz-sel="0">
        <div class="challengeTypeLabel">
            <p>Sfide Completate</p>
            <span>
                <p>{{ $ownerChallengesIn->where("state", "expired")->count() + $ownerChallengesOut->where("state", "expired")->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="challengeCollection">
            @foreach($ownerChallengesIn->where("state", "expired") as $challengeIn)
                @php
                    $totalExp = $challengeIn->from_user_exp + $challengeIn->to_user_exp;
                    if($totalExp == 0) {
                        $fromPercentage = 0;
                        $toPercentage = 0;
                    } else {
                        $fromPercentage = round((($challengeIn->from_user_exp / $totalExp) * 100), 2);
                        $toPercentage = round((($challengeIn->to_user_exp / $totalExp) * 100), 2);
                    }
                @endphp
                
                <span class="challengeContainer expired">
                    <span class="fromUser" style="--percentage: {{ $fromPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$challengeIn->fromUser->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeIn->fromUser->username }}</p>
                            <p>{{ $challengeIn->from_user_exp }}</p>
                        </span>
                    </span>

                    <span class="toUser" style="--percentage: {{ $toPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$currentOwner->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeIn->to_user_exp }}</p>
                            <p>Me</p>
                        </span>
                    </span>
                    
                    <p class="challengeCooldown">Terminata il {{ $challengeIn->expiring_at }}</p>
                </span>
            @endforeach

            @foreach($ownerChallengesOut->where("state", "expired") as $challengeOut)
                @php
                    $totalExp = $challengeOut->from_user_exp + $challengeOut->to_user_exp;
                    if($totalExp == 0) {
                        $fromPercentage = 0;
                        $toPercentage = 0;
                    } else {
                        $fromPercentage = round((($challengeOut->from_user_exp / $totalExp) * 100), 2);
                        $toPercentage = round((($challengeOut->to_user_exp / $totalExp) * 100), 2);
                    }
                @endphp
                
                <span class="challengeContainer expired">
                    <span class="fromUser" style="--percentage: {{ $fromPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$challengeOut->fromUser->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeOut->fromUser->username }}</p>
                            <p>{{ $challengeOut->from_user_exp }}</p>
                        </span>
                    </span>

                    <span class="toUser" style="--percentage: {{ $toPercentage }}%;">
                        <img src="{{ Storage::url('images/avatars/'.$currentOwner->avatar->minisize_url) }}">
                        <span>
                            <p>{{ $challengeOut->to_user_exp }}</p>
                            <p>Me</p>
                        </span>
                    </span>
                    
                    <p class="challengeCooldown">Terminata il {{ Carbon::parse($challengeOut->expiring_at)->format("d M Y \a\l\l\\e h:m") }}</p>
                </span>
            @endforeach
        </div>
    </div>
</div>