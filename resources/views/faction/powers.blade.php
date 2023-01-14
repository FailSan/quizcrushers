@php
    use App\Models\Power;
    $powers = Power::where('faction_id', $faction)->get();
@endphp

@foreach($powers as $power)
    <div class="powerContainer">
        <span class="powerImage"><img src="{{ Storage::url('images/powers/'.$power->fullsize_url) }}"></span>
        <span class="powerName">{{ $power->name }}</span>
        <span class="powerCooldown">Tempo di Ricarica: {{ $power->cooldown }}h</span>
        <span class="powerRequiredLvl">Livello Richiesto: {{ $power->required_lvl }}</span>
        <span class="powerDescription">{{ $power->description }}</span>
    </div>
@endforeach