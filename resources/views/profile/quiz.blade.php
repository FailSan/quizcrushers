@php
    $ownerQuizzes = $currentOwner->quizzes;

    $mathQuizzes = $ownerQuizzes->where("type", "math")->sortBy("created_at");
    $physicsQuizzes = $ownerQuizzes->where("type", "physics")->sortBy("created_at");
    $logicQuizzes = $ownerQuizzes->where("type", "logic")->sortBy("created_at");
    $easyQuizzes = $ownerQuizzes->where("type", "easy")->sortBy("created_at");
    $mediumQuizzes = $ownerQuizzes->where("type", "medium")->sortBy("created_at");
    $hardQuizzes = $ownerQuizzes->where("type", "hard")->sortBy("created_at");
@endphp

<div class="quizFrame">
    <div class="quizType" data-quiz-sel="1">
        <div class="quizTypeLabel">
            <p>Matematica</p>
            <span>
                <p>{{ $mathQuizzes->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="quizCollection">
            @foreach($mathQuizzes as $mathQuiz)
            <span class="quizContainer">
                <p>Quiz n. {{ $loop->iteration }}</p>
                <p>{{ $mathQuiz->tests->count() }} / 9</p>
            </span>
            @endforeach
        </div>
    </div>

    <div class="quizType" data-quiz-sel="0">
        <div class="quizTypeLabel">
            <p>Fisica</p>
            <span>
                <p>{{ $physicsQuizzes->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="quizCollection">
            @foreach($physicsQuizzes as $physicsQuiz)
            <span class="quizContainer">
                <p>Quiz n. {{ $loop->iteration }}</p>
                <p>{{ $physicsQuiz->tests->count() }} / 9</p>
            </span>
            @endforeach
        </div>
    </div>

    <div class="quizType" data-quiz-sel="0">
        <div class="quizTypeLabel">
            <p>Logica</p>
            <span>
                <p>{{ $logicQuizzes->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="quizCollection">
            @foreach($logicQuizzes as $logicQuiz)
            <span class="quizContainer">
                <p>Quiz n. {{ $loop->iteration }}</p>
                <p>{{ $logicQuiz->tests->count() }} / 9</p>
            </span>
            @endforeach
        </div>
    </div>

    <div class="quizType" data-quiz-sel="0">
        <div class="quizTypeLabel">
            <p>Facile</p>
            <span>
                <p>{{ $easyQuizzes->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="quizCollection">
            @foreach($easyQuizzes as $easyQuiz)
            <span class="quizContainer">
                <p>Quiz n. {{ $loop->iteration }}</p>
                <p>{{ $easyQuiz->tests->count() }} / 9</p>
            </span>
            @endforeach
        </div>
    </div>

    <div class="quizType" data-quiz-sel="0">
        <div class="quizTypeLabel">
            <p>Medio</p>
            <span>
                <p>{{ $mediumQuizzes->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="quizCollection">
            @foreach($mediumQuizzes as $mediumQuiz)
            <span class="quizContainer">
                <p>Quiz n. {{ $loop->iteration }}</p>
                <p>{{ $mediumQuiz->tests->count() }} / 9</p>
            </span>
            @endforeach
        </div>
    </div>

    <div class="quizType" data-quiz-sel="0">
        <div class="quizTypeLabel">
            <p>Difficile</p>
            <span>
                <p>{{ $hardQuizzes->count() }}</p>
                <p>+</p>
            </span>
        </div>

        <div class="quizCollection">
            @foreach($hardQuizzes as $hardQuiz)
            <span class="quizContainer">
                <p>Quiz n. {{ $loop->iteration }}</p>
                <p>{{ $hardQuiz->tests->count() }} / 9</p>
            </span>
            @endforeach
        </div>
    </div>
</div>