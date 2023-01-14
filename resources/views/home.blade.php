<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Quiz Crushers - Spacca un Quiz dopo l'altro e scala le Classifiche!</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel ="stylesheet" href="styles/public/main.css">
        @guest
            <link rel ="stylesheet" href="styles/public/forms.css">
        @endguest
        
        <!-- Script -->
        @guest
            <script src="javascript/public/graphics.js" defer></script>
            <script src="javascript/public/validation.js" defer></script>
        @endguest

    </head>
    <body>

        <navbar>
            <a href="/">Home</a>
            <a href="#" data-link-index="0">Info</a>
            <a href="#" data-link-index="1">Login</a>
            <a href="#" data-link-index="2">Registrazione</a>
        </navbar>

        <main class="mainFrame">
            <div class="brain">
                <img class="left" src="{{ Storage::url('images/leftBrain.png') }}">
                <img class="shadow left" src="{{ Storage::url('images/leftBrain.png') }}">
            </div>
                
            <div class="mainContent">
                <header class="siteTitle">
                    <svg viewBox="0 0 250 30">
                        <text x="0" y="20" textLength="250" lengthAdjust="spacing">Quiz Crushers</text>
                    </svg>
                </header>

                <section class="siteContent">
                    <article data-section-index="0" data-section-sel="1">@include('home.description')</article>

                    <article data-section-index="1" data-section-sel="0">@include('home.login')</article>

                    <article data-section-index="2" data-section-sel="0">@include('home.register')</article>

                    <article data-section-index="3" data-section-sel="0">@include('home.dialog')</article>
                </section>
            </div>
            
            <div class="brain">
                <img class="right"src="{{ Storage::url('images/rightBrain.png') }}">
                <img class="shadow right" src="{{ Storage::url('images/rightBrain.png') }}">
            </div>
        </main>

        <footer>
            <p>Footer</p>
        </footer>
    </body>
</html>
