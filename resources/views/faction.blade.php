@php

    use App\Models\Faction;

@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Quiz Crushers - Spacca un Quiz dopo l'altro e scala le Classifiche!</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel ="stylesheet" href="styles/private/faction.css">
        
        <!-- Script -->
        <script src="javascript/private/faction.js" defer></script>

    </head>
    <body>

        <navbar>
            <a href="/dashboard">Home</a>
            <a href="/logout">Logout</a>
        </navbar>

        <main>
            <div class="factionPrologue">
                <span class="prologueMain">Scegli la tua Fazione e sfida il gioco per farla vincere!</span>
                <span class="prologueLabel">Scegli saggiamente. Non potrai cambiare idea in seguito!</span>
            </div>

            <div class="factionFrame dark">
                <div class="factionChooser">
                    <span data-faction="dark">Oscurità</span>
                    <span data-faction="light">Luce</span>
                </div>

                <div class="factionContainer" data-faction="dark" data-faction-sel="1">
                    <div class="factionImage">
                        <img src="{{ Storage::url('images/leftBrain.png') }}">
                        <img style="--zoomDir: -50%, -10%;" class="shadow" src="{{ Storage::url('images/leftBrain.png') }}">
                    </div>

                    <div class="factionDetails">
                        <div>
                            <div class="detailContainer" data-detail-sel="1">
                                <span class="detailLabel" data-detail="description">
                                    <p>Descrizione</p>
                                    <p>+</p>
                                </span>
                                <span class="detailBody">{{ Faction::find(2)->description }}</span>
                            </div>
                            
                            <div class="detailContainer" data-detail-sel="0">
                                <span class="detailLabel" data-detail="avatar">
                                    <p>Avatar</p>
                                    <p>+</p>
                                </span>
                                <span class="detailBody">@include('faction.avatars', ['faction' => 2])</span>
                            </div>

                            
                            <div class="detailContainer" data-detail-sel="0">
                                <span class="detailLabel" data-detail="powers">
                                    <p>Poteri</p>
                                    <p>+</p>
                                </span>
                                <span class="detailBody">@include('faction.powers', ['faction' => 2])</span>
                            </div>
                        </div>

                        <button data-faction="dark">Scegli l'Oscurità</button>   
                    </div>
                </div>

                <div class="factionContainer" data-faction="light" data-faction-sel="0">
                    <div class="factionImage">
                        <img src="{{ Storage::url('images/rightBrain.png') }}">
                        <img style="--zoomDir: 50%, -10%;" class="shadow" src="{{ Storage::url('images/rightBrain.png') }}">
                    </div>

                    <div class="factionDetails">
                        <div>
                            <div class="detailContainer" data-detail-sel="1">
                                <span class="detailLabel" data-detail="description">
                                    <p>Descrizione</p>
                                    <p>+</p>
                                </span>
                                <span class="detailBody">{{ Faction::find(1)->description }}</span>
                            </div>
                            
                            <div class="detailContainer" data-detail-sel="0">
                                <span class="detailLabel" data-detail="avatar">
                                    <p>Avatar</p>
                                    <p>+</p>
                                </span>
                                <span class="detailBody">@include('faction.avatars', ['faction' => 1])</span>
                            </div>

                            
                            <div class="detailContainer" data-detail-sel="0">
                                <span class="detailLabel" data-detail="powers">
                                    <p>Poteri</p>
                                    <p>+</p>
                                </span>
                                <span class="detailBody">@include('faction.powers', ['faction' => 1])</span>
                            </div>
                        </div>
                        
                        <button data-faction="light">Scegli la Luce</button>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <p>Footer</p>
        </footer>
    </body>
</html>