@php
    use Carbon\Carbon;
    use Carbon\CarbonInterface;
@endphp

<div class="challengeType" data-challenge-state="running" data-challenge-sel="1">
    <span class="challengeTypeLabel">
        <p><strong>Sfide in Corso</strong></p>
        <p><strong>{{ $currentUser->challengesIn->where("state", "running")->count() + $currentUser->challengesOut->where("state", "running")->count() }}</strong></p>
    </span>
    <div class="challengeTypeBody">
        @foreach($currentUser->challengesIn->where("state", "running") as $challengeIn)
            <div class="challengeRunning">
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
                <span class="fromUser" style="--percentage: {{ $fromPercentage }}%;">
                    <img src="{{ Storage::url('images/avatars/'.$challengeIn->fromUser->avatar->minisize_url) }}">
                    <span>
                        <p>{{ $challengeIn->fromUser->username }}</p>
                        <p>{{ $challengeIn->from_user_exp }}</p>
                    </span>
                </span>

                <span class="toUser" style="--percentage: {{ $toPercentage }}%;">
                    <img src="{{ Storage::url('images/avatars/'.$currentUser->avatar->minisize_url) }}">
                    <span>
                        <p>{{ $challengeIn->to_user_exp }}</p>
                        <p>Me</p>
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
                    <p>Scade tra {{ $expiring_at->diffForHumans(Carbon::now(), CarbonInterface::DIFF_ABSOLUTE) }}</p>
                @endif
            </div>
        @endforeach

        @foreach($currentUser->challengesOut->where("state", "running") as $challengeOut)
            <div class="challengeRunning">
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
                <span class="fromUser" style="--percentage: {{ $fromPercentage }}%;">
                    <img src="{{ Storage::url('images/avatars/'.$currentUser->avatar->minisize_url) }}">
                    <span>
                        <p>Me</p>
                        <p>{{ $challengeOut->from_user_exp }}</p>
                    </span>
                </span>

                <span class="toUser" style="--percentage: {{ $toPercentage }}%;">
                    <img src="{{ Storage::url('images/avatars/'.$challengeOut->toUser->avatar->minisize_url) }}">
                    <span>
                        <p>{{ $challengeOut->to_user_exp }}</p>
                        <p>{{ $challengeOut->toUser->username }}</p>
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
                    <p>Scade tra {{ $expiring_at->diffForHumans(Carbon::now(), CarbonInterface::DIFF_ABSOLUTE) }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="challengeType" data-challenge-state="waiting" data-challenge-sel="0">
    <span class="challengeTypeLabel">
        <p><strong>Sfide in Attesa</strong></p>
        <p><strong>{{ $currentUser->challengesIn->where("state", "waiting")->count() + $currentUser->challengesOut->where("state", "waiting")->count() }}</strong></p>
    </span>
    <div class="challengeTypeBody">
        @foreach($currentUser->challengesIn->where("state", "waiting") as $challengeIn)
            <span class="challengeWaiting">
                <img src="{{ Storage::url('images/avatars/'.$challengeIn->fromUser->avatar->minisize_url) }}">
                <p><strong>{{ $challengeIn->fromUser->username }}</strong></p>
                <a data-user-id="{{ $challengeIn->fromUser->id }}" data-challenge-action="accept">Accetta</a>
                <a data-user-id="{{ $challengeIn->fromUser->id }}" data-challenge-action="refuse">Rifiuta</a>
            </span>
        @endforeach

        @foreach($currentUser->challengesOut->where("state", "waiting") as $challengeOut)
            <span class="challengeWaiting">
                <img src="{{ Storage::url('images/avatars/'.$challengeOut->toUser->avatar->minisize_url) }}">
                <p><strong>{{ $challengeOut->toUser->username }}</strong></p>
                <a data-user-id="{{ $challengeOut->toUser->id }}" data-challenge-action="delete">Annulla</a>
            </span>
        @endforeach
    </div>
</div>