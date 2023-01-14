<form action="#" method="POST" id="loginForm">
    @csrf
    <label for="user">Username o Email</label>
    <input type="text" name="user" id="user" placeholder="Username o Email">

    <label for="password">Password</label>
    <input type="password" name="password" placeholder="Password">

    <label class="checkLabel">
        <p>Ricordami</p>
        <input type="checkbox" name="remember_token" id="remember_token" value="1">
    </label>
    
    <input type="submit" value="Accedi">
</form>