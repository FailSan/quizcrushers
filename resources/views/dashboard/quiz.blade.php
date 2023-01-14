<div class="quizIntro">
    <span class="textBig">Benvenuto nella Dashboard!</span>
    <span class="textLittle">
        Scegli un Quiz e completalo, ad ogni Test che superi guadagnerai esperienza per te e la tua Fazione.<br>
        Puoi scegliere tra varie tipologie di Quiz, ma non potrai avviare contemporaneamente più Quiz della stessa tipologia.
    </span>
</div>

<div class="quizGroup">
    <div class="quizContainer">
        <p>Quiz Matematica</p>
        <img src="{{ Storage::url('images/quizzes/math.png') }}">
        <span>
            Metti alla prova le tue conoscenze Matematiche. Affronterai problemi di Aritmetica, Studio di Funzioni e Analisi.
        </span>
        <a href="quiz/math">
            <button>
                @if($currentUser->quizzes()->where("type", "math")->where("closed", 0)->exists())
                    Torna al vecchio Quiz!
                @else
                    Crea nuovo Quiz!
                @endif
            </button>
        </a>
    </div>

    <div class="quizContainer">
        <p>Quiz Fisica</p>
        <img src="{{ Storage::url('images/quizzes/physics.png') }}">
        <span>
            Metti alla prova le tue conoscenze Fisiche. Affronterai problemi di Termodinamica, Meccanica ed Elettromagnetismo.
        </span>
        <a href="quiz/physics">
            <button>
                @if($currentUser->quizzes()->where("type", "physics")->where("closed", 0)->exists())
                    Torna al vecchio Quiz!
                @else
                    Crea nuovo Quiz!
                @endif
            </button>
        </a>
    </div>

    <div class="quizContainer">
        <p>Quiz Logica</p>
        <img src="{{ Storage::url('images/quizzes/logic.png') }}">
        <span>
            Metti alla prova la tua capacità di comprendere. Affronterai problemi di Comprensione del Testo, ricerca di Sequenze e simili.
        </span>
        <a href="quiz/logic">
            <button>
                @if($currentUser->quizzes()->where("type", "logic")->where("closed", 0)->exists())
                    Torna al vecchio Quiz!
                @else
                    Crea nuovo Quiz!
                @endif
            </button>
        </a>
    </div>
</div>

<div class="quizGroup">
    <div class="quizContainer">
        <p>Quiz Facile</p>
        <img src="{{ Storage::url('images/quizzes/easy.png') }}">
        <span>
            Vuoi vincere facile? Questo è il Quiz giusto. Troverai quesiti di tutte le tipologie ma di bassa difficoltà.
        </span>
        <a href="quiz/easy">
            <button>
                @if($currentUser->quizzes()->where("type", "easy")->where("closed", 0)->exists())
                    Torna al vecchio Quiz!
                @else
                    Crea nuovo Quiz!
                @endif
            </button>
        </a>
    </div>

    <div class="quizContainer">
        <p>Quiz Medio</p>
        <img src="{{ Storage::url('images/quizzes/medium.png') }}">
        <span>
            Ci stai prendendo gusto? Bene! Ora prova questo Quiz. Troverai quesiti di tutte le tipologie con difficoltà media.
        </span>
        <a href="quiz/medium">
            <button>
                @if($currentUser->quizzes()->where("type", "medium")->where("closed", 0)->exists())
                    Torna al vecchio Quiz!
                @else
                    Crea nuovo Quiz!
                @endif
            </button>
        </a>
    </div>

    <div class="quizContainer">
        <p>Quiz Difficile</p>
        <img src="{{ Storage::url('images/quizzes/hard.png') }}">
        <span>
            Stufo di avere sempre ragione? Prova a risolvere questo Quiz. Troverai quesiti di tutte le tipologie ma di alta difficoltà.
        </span>
        <a href="quiz/hard">
            <button>
                @if($currentUser->quizzes()->where("type", "hard")->where("closed", 0)->exists())
                    Torna al vecchio Quiz!
                @else
                    Crea nuovo Quiz!
                @endif
            </button>
        </a>
    </div>
</div>