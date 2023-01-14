@extends("dashboard")

@section("title", "Test")

@section("styles")
<link rel="stylesheet" href="{{ asset('styles/private/test.css') }}">
@endsection

@section("scripts")
<script src="{{ asset('javascript/private/test.js') }}" defer></script>
@endsection

@section("content")
    <div class="testTitle" data-test-id="{{ $currentTest->id }}">Test {{ $currentTest->id }}</div>

    <div class="testDescription">
        Descrizione Test: {{ $currentTest->question->description }}
    </div>

    <form action="#" method="GET" class="testForm" id="testForm">
        @foreach($currentTest->options as $option)
            <label data-option-id="{{ $option->id }}" @class([
                "optionLabel",
                "optionCorrect" => ($currentTest->hasAnswer() && $option->correct == 1),
                "optionNotCorrect" => ($currentTest->hasAnswer() && $option->correct == 0)
            ])>
                <span class="optionButton">
                    <p class="optionDesc">{{ $option->description }}</p>
                    <input type="radio" value="{{ $option->id }}" name="testOptions" @checked($option->pivot->choosen) @disabled($currentTest->hasAnswer())>
                    <p class="optionRadio"></p>
                </span>
                <p style="--percentage: 0;" class="optionPercentage hidden"></p>
            </label>
        @endforeach
        <a class="backButton" href="/quiz/{{ $currentTest->quiz->type }}/{{ $currentTest->quiz->id }}">Torna al Quiz</a>
        <input type="submit" value="Invia" name="optionSubmit" disabled>
    </form>
@endsection