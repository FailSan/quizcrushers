@php
    
    use App\Models\Degree;

    $degrees = Degree::all();

@endphp

<form action="#" method="POST" id="registerForm">
    @csrf
    <fieldset data-reg-index="0" data-reg-sel="1">
        <legend>Informazioni Personali</legend>
        
        <fieldset class="genderFieldset">
            <legend>Genere</legend>
            <label>Maschio<input type="radio" name="gender" value="Maschio" checked></label>
            <label>Femmina<input type="radio" name="gender" value="Femmina"></label>
            <label>Altro<input type="radio" name="gender" value="Altro"></label>
        </fieldset>
        <p class="errorLabel"></p>

        <label for="name">Nome</label>
        <input type="text" name="name" id="name" placeholder="Nome">  
        <p class="errorLabel"></p>  
        <label for="surname">Cognome</label>
        <input type="text" name="surname" id="surname" placeholder="Cognome">
        <p class="errorLabel"></p>
        <label for="birthdate">Data di Nascita</label>
        <input type="date" name="birthdate" id="birthdate" min="1922-01-01" max="2005-01-01">
        <p class="errorLabel"></p>
    
    </fieldset>

    <fieldset data-reg-index="1" data-reg-sel="0">
        
        <legend>Dati Iscrizione</legend>
        
        <label for="email">E-Mail</label>
        <input type="email" name="email" id="email" placeholder="email@gmail.com">
        <p class="errorLabel"></p>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Username">
        <p class="errorLabel"></p>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password">
        <p class="errorLabel"></p>
        <label for="password_confirmation">Conferma Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Conferma Password">
    
    </fieldset>

    <fieldset data-reg-index="2" data-reg-sel="0">
        <legend>Info Scolastiche</legend>

        <label for="degree">Tipo Diploma</label>
        <select name="degree" id="degree">
            @foreach ($degrees as $degree)
                <option value="{{ $degree->id }}">{{ $degree->name }}</option>
            @endforeach
        </select>
        <p class="errorLabel"></p>
        <label for="degree_vote">Voto Diploma</label>
        <input type="number" name="degree_vote" id="degree_vote" min="60" max="100" value="60">
        <p class="errorLabel"></p>
        <label for="math_vote">Voto Matematica Medio</label>
        <input type="number" name="math_vote" id="math_vote" min="1" max="10" step="0.5" value="6">
        <p class="errorLabel"></p>
        <label for="physics_vote">Voto Fisica Medio</label>
        <input type="number" name="physics_vote" id="physics_vote" min="1" max="10" step="0.5" value="6">
        <p class="errorLabel"></p>

        <input type="submit" value="Registrati">
    </fieldset>

</form>

<div class="circleSlider">
    <span data-circle-index="0" data-circle-sel="1"></span>
    <span class="disabled" data-circle-index="1" data-circle-sel="0"></span>
    <span class="disabled" data-circle-index="2" data-circle-sel="0"></span>
</div>