<?php require_once 'include\authenticate.php' ?>

<main>
    <h2>Login</h2>
    <form action="index.php" method="post">
        <div>
            <label for="username">Gebruikersnaam</label>
            <input id="username" type="text" maxlength="100" name="username" required>
        </div>
        <div>
            <label for="password">Wachtwoord</label>
            <input id="password" type="password" maxlength="128" name="password" required>
        </div>
        <div>
            <input type="submit" value="Login" name="authenticateUser">
        </div>
    </form>