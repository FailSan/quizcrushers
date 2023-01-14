@php
    $baseAvatars = $avatars->where("faction_id", null)->groupBy("required_lvl");
    $factionAvatars = $avatars->where("faction_id", $user->faction_id)->groupBy("required_lvl");
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Quiz Crushers - Spacca un Quiz dopo l'altro e scala le Classifiche!</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel ="stylesheet" href="styles/private/avatar.css">
        
        <!-- Script -->
        <script src="javascript/private/avatar.js" defer></script>

    </head>
    <body @class(["light" => $user->faction_id == "1", "dark" => $user->faction_id == "2"])>

        <navbar>
            <a href="/dashboard">Home</a>
            <a href="/logout">Logout</a>
        </navbar>

        <main>
            <div class="avatarPrologue">
                <span class="prologueMain">Scegli il tuo Avatar tra quelli a tua dispozione!</span>
                <span class="prologueLabel">Non preoccuparti, nuovi avatar saranno disponibili con l'aumento del tuo livello!</span>
            </div>

            <div class="avatarFrame">
                <div class="avatarGroups">
                    <div class="avatarBase" data-group-sel="1">
                        <span class="avatarGroupLabel">
                            <p>Avatar Base</p>
                            <p>+</p>
                        </span>
                        @foreach($baseAvatars as $group)
                            @foreach($group as $avatar)
                            <div class="avatarContainer">
                                @php
                                    if($avatar->required_lvl > $user->level)
                                        $available = 0;
                                    else
                                        $available = 1;
                                @endphp
                                <img src="{{ Storage::url('images/avatars/'.$avatar->minisize_url) }}" data-avatar-id="{{ $avatar->id }}" {{ "data-available=".$available }} data-avatar-sel="0">
                                <span class="avatarLevels">Livello {{ $avatar->required_lvl }}</span>
                                @if($available == 0)
                                    <p>Non disponibile</p>
                                @endif
                            </div>
                            @endforeach
                        @endforeach
                    </div>

                    <div class="avatarFaction" data-group-sel="0">
                        <span class="avatarGroupLabel">
                            <p>Avatar Fazione</p>
                            <p>+</p>
                        </span>
                        @foreach($factionAvatars as $group)
                            @foreach($group as $avatar)
                            <div class="avatarContainer">
                                @php
                                    if($avatar->required_lvl > $user->level)
                                        $available = 0;
                                    else
                                        $available = 1;
                                @endphp
                                <img src="{{ Storage::url('images/avatars/'.$avatar->minisize_url) }}" {{ "data-available=".$available }}>
                                <span class="avatarLevels">Livello {{ $avatar->required_lvl }}</span>
                                @if($available == 0)
                                    <p>Non disponibile</p>
                                @endif
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="avatarDisplay">
                    <img src="" data-avatar-id="">
                    <button class="hidden">Conferma</button>
                </div>
            </div>
        </main>

        <footer>
            <p>Footer</p>
        </footer>
    </body>
</html>