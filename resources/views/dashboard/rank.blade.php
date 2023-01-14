@php
    use App\Models\User;

    $myFaction = $currentUser->faction->name;
    if($myFaction == "Luce") {
        $myColor = "rgba(255, 255, 255, 1)";
        $enemyColor = "rgba(0, 0, 0, 1)";
    } else {
        $myColor = "rgba(0, 0 , 0, 1)";
        $enemyColor = "rgba(255, 255, 255, 1)";
    }

    $myFactionExp = User::where("faction_id", $currentUser->faction_id)->sum("experience");
    $enemyFactionExp = User::where("faction_id", "!=", $currentUser->faction_id)->sum("experience");

    $myFactionPercentage = round(($myFactionExp)/($myFactionExp + $enemyFactionExp), 2) * 100;

    switch($showFlag) {
        case "light":
            $filteredUsers = User::where("faction_id", 1);
            break;
        case "dark":
            $filteredUsers = User::where("faction_id", 2);
            break;
        default:
            $filteredUsers = User::where("faction_id", 1)->orWhere("faction_id", 2);
            break;
    }
    
    if($stringFlag) {
        $filteredUsers = $filteredUsers->where("username", "like", "%".$stringFlag."%");
    }

    switch($sortFlag) {
        case "rate":
            $sortedUsers = $filteredUsers->get()->sortByDesc("correct_rate");
            break;
        default:
            $sortedUsers = $filteredUsers->get()->sortByDesc("experience");
            break;
    }
@endphp

<div class="factionExperience">
    <svg viewBox="0 0 100 100">
        <circle class="enemyCircle" style="--color: {{ $enemyColor }};"></circle>
        <circle class="myCircle" style="--color: {{ $myColor }}; --percentage: calc(({{ $myFactionPercentage }}% - 6px) * 3.14159);"></circle>
    </svg>
</div>

<div class="userRank">
    @foreach($sortedUsers as $userRank)
        <div class="userRankContainer" data-faction-id="{{ $userRank->faction_id }}" data-user-sel="0">
            <img class="userRankAvatar" src="{{ Storage::url('images/avatars/'.$userRank->avatar->minisize_url) }}">
            <span class="userRankInfo">
                <p>
                    @if($currentUser->id == $userRank->id)
                        <strong>{{ $userRank->username }}</strong>
                    @else
                        {{ $userRank->username }}
                    @endif
                </p>

                <p>
                    @if($currentUser->id == $userRank->id)
                        <strong>{{ $userRank->experience }} || {{ $userRank->correct_rate }}</strong>
                    @else
                        {{ $userRank->experience }} || {{ $userRank->correct_rate }}
                    @endif
                </p>
            </span>
            <span class="userRankNumber">{{ $loop->iteration++ }}</span>
            <span class="userRankActions">
                @php
                    $challengeWaitingToUser = $currentUser->challengesOut
                        ->where("state", "waiting")
                        ->where("to_user_id", $userRank->id);

                    $challengeWaitingFromUser = $currentUser->challengesIn
                        ->where("state", "waiting")
                        ->where("from_user_id", $userRank->id);

                    $challengeToUser = $currentUser->challengesOut
                        ->where("state", "!=", "expired")
                        ->where("to_user_id", $userRank->id);

                    $challengeFromUser = $currentUser->challengesIn
                        ->where("state", "!=", "expired")
                        ->where("from_user_id", $userRank->id);
                @endphp
                    <a data-user-id="{{ $userRank->id }}">Vai al Profilo</a>

                @if($challengeWaitingFromUser->count())
                    <p>
                        <a data-user-id="{{ $userRank->id }}" data-challenge-action="accept">Accetta Sfida</a>
                        <a data-user-id="{{ $userRank->id }}" data-challenge-action="refuse">Rifiuta Sfida</a>
                    </p>
                @endif

                @if($challengeWaitingToUser->count())
                    <a data-user-id="{{ $userRank->id }}" data-challenge-action="delete">Annulla Sfida</a>
                @endif

                @if(!$challengeFromUser->count() && !$challengeToUser->count() && $currentUser->id != $userRank->id)
                    <a data-user-id="{{ $userRank->id }}" data-challenge-action="create">Proponi Sfida</a>
                @endif
            </span>
        </div>
    @endforeach
</div>

<form action="#" method="GET" id="rankFilterForm" class="userRankSearch">
    <fieldset class="showRadio">
        <legend>Visualizza:</legend>
        <label>Luce<input type="radio" value="light" name="showRadio" @checked($showFlag == "light")></label>
        <label>Tutti<input type="radio" value="all" name="showRadio" @checked($showFlag == "all")></label>
        <label>Oscurità<input type="radio" value="dark" name="showRadio" @checked($showFlag == "dark")></label>
    </fieldset>
    
    <fieldset class="sortRadio">
        <legend>Ordine:</legend>
        <label>Esperienza<input type="radio" value="exp" name="sortRadio" @checked($sortFlag == "exp")></label>
        <label>Correct Rate<input type="radio" value="rate" name="sortRadio" @checked($sortFlag == "rate")></label>
    </fieldset>
    
    <input type="text" name="stringInput" value="{{ $stringFlag }}" placeholder="Username">
</form>