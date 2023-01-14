@extends("dashboard")

@section("title", "Quiz")

@section("styles")
    <link rel="stylesheet" href="{{ asset('styles/private/quiz.css') }}">
@endsection

@section("content")
    <div class="quizTitle">
        @switch ($currentQuiz->type)

            @case ("math")
                Quiz di Matematica
                @break;
            @case ("physics")
                Quiz di Fisica
                @break;
            @case ("logic")
                Quiz di Logica
                @break;
            @case ("easy")
                Quiz di difficoltà Facile
                @break;
            @case ("medium")
                Quiz di difficoltà Media
                @break;
            @case ("hard")
                Quiz di difficoltà Difficile
                @break;
            @default
                @break;

        @endswitch
    </div>

    <div class="quizDescription">
        @switch ($currentQuiz->type)

            @case ("math")
                <span>
                    Questo è un Quiz di Matematica.<br>
                    Troverai quesiti su Limiti, Derivate e sulle Proprietà delle funzioni elementari.<br>
                @break
            @case ("physics")
                <span>
                    Questo è un Quiz di Fisica.<br>
                    Troverai quesiti su Cinematica, Meccanica, Termodinamica ed Elettromagnetismo.<br>
                @break
            @case ("logic")
                <span>
                    Questo è un Quiz di Logica.<br>
                    Troverai quesiti di Comprensione del Testo o di ricerca dei giusti Pattern per completare una sequenza.<br>
                @break
            @case ("easy")
                <span>
                    Questo è un Quiz generico di difficoltà "Facile".<br>
                    Troverai quesiti di Matematica, Fisica e Logica, ma questi avranno una difficoltà massima bloccata.<br>
                @break;
            @case ("medium")
                <span>
                    Questo è un Quiz generico di difficoltà "Media".<br>
                    Troverai quesiti di Matematica, Fisica e Logica, ma questi avranno una difficoltà massima bloccata.<br>
                @break;
            @case ("hard")
                <span>
                    Questo è un Quiz generico di difficoltà "Difficile".<br>
                    Troverai quesiti di Matematica, Fisica e Logica, ma questi avranno una difficoltà massima bloccata.<br>
                @break;
            @default
                @break;

        @endswitch
            Per ogni quesito al quale risponderai correttamente ti verrà data una quantità variabile di punti Esperienza.<br>
            Più è difficile un quesito, maggiore sarà la quantità di punti Esperienza che cederà una volta risolto.<br><br>
            Cosa aspetti? Comincia il Quiz cliccando sui Test qui sotto!
        </span>
    </div>

    <div class="quizTests">
        @if($currentQuiz->tests->isEmpty())
            <span><a href="/test/{{ $currentQuiz->id }}">Nuovo Test</a></span>
        @else
            @foreach($currentQuiz->tests->sortBy("created_at") as $test)
                <span><a href="/test/{{ $currentQuiz->id }}/{{ $test->id }}">Test {{ $loop->iteration }}</a></span>
            @endforeach
            @if($currentQuiz->tests->count() < 9 && $currentQuiz->tests->sortByDesc("created_at")->first()->hasAnswer())
                <span><a href="/test/{{ $currentQuiz->id }}">Nuovo Test</a></span>
            @endif
        @endif
    </div>
@endsection