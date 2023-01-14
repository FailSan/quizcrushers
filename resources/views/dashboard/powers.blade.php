<div class="powerFrame">
    @foreach($currentUser->faction->powers as $power)
        <div @class([
            "powerContainer", 
            "disabled" => ($currentUser->level < $power->required_lvl || $currentUser->powerCooldown($power->id) > 0)
            ]) data-power-id="{{ $power->id }}">
            <img src="{{ Storage::url('images/powers/'.$power->fullsize_url) }}">
            <span class="powerName">{{ $power->name }}</span>
            <span class="powerCooldown">
                @if($currentUser->powerCooldown($power->id) > 0)
                    Attendi {{ $currentUser->powerCooldown($power->id) }} minuti.
                @else
                    Ricarica: {{ $power->cooldown }}h
                @endif
            </span>
            <span class="powerRequiredLvl">Livello {{ $power->required_lvl }}</span>
            <span class="powerDescription">{{ $power->description }}</span>
        </div>
    @endforeach
</div>