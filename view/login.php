<?php
require_once("include/login.php"); ?>

<h2>Login</h2>
<form action="index.php" method="post">
    <div>
        <label for="username">Gebruikersnaam:</label>
        <input id="username" type="text" name="user" required>
    </div>
    <div>
        <label for="password">Wachtwoord:</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div>
        <input type="submit" name="submit" value="Login">
    </div>
</form>