@php
    use App\Models\Avatar;
    $avatars = Avatar::all();
@endphp

<div class="avatarsContainer">
    <span data-level="3" data-level-sel="1">
        @foreach($avatars->where('faction_id', $faction)->where('required_lvl', '3') as $avatar)
            <img src="{{ Storage::url('images/avatars/'.$avatar->fullsize_url) }}">
        @endforeach
    </span>
    
    <span data-level="6" data-level-sel="0">
        @foreach($avatars->where('faction_id', $faction)->where('required_lvl', '6') as $avatar)
            <img src="{{ Storage::url('images/avatars/'.$avatar->fullsize_url) }}">
        @endforeach
    </span>
    
    <span data-level="9" data-level-sel="0">
        @foreach($avatars->where('faction_id', $faction)->where('required_lvl', '9') as $avatar)
            <img src="{{ Storage::url('images/avatars/'.$avatar->fullsize_url) }}">
        @endforeach
    </span>

    <span data-level="12" data-level-sel="0">
        @foreach($avatars->where('faction_id', $faction)->where('required_lvl', '12') as $avatar)
            <img src="{{ Storage::url('images/avatars/'.$avatar->fullsize_url) }}">
        @endforeach
    </span>

    <span data-level="15" data-level-sel="0">
        @foreach($avatars->where('faction_id', $faction)->where('required_lvl', '15') as $avatar)
            <img src="{{ Storage::url('images/avatars/'.$avatar->fullsize_url) }}">
        @endforeach
    </span>
    
    <span data-level="18" data-level-sel="0">
        @foreach($avatars->where('faction_id', $faction)->where('required_lvl', '18') as $avatar)
            <img src="{{ Storage::url('images/avatars/'.$avatar->fullsize_url) }}">
        @endforeach
    </span>
</div>

<div class="avatarsLevels">
    <span data-level="3" data-level-sel="1">Livello 3</span>
    <span data-level="6" data-level-sel="0">Livello 6</span>
    <span data-level="9" data-level-sel="0">Livello 9</span>
    <span data-level="12" data-level-sel="0">Livello 12</span>
    <span data-level="15" data-level-sel="0">Livello 15</span>
    <span data-level="18" data-level-sel="0">Livello 18</span>
</div>