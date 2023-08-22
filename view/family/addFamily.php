<h1>Familie toevoegen</h1>

<form action="index.php" method="post">
    <div>
        <label for="firstName">Voornaam</label>
        <input id="firstName" type="text" name="firstName" autofocus="on" required>
    </div>
    <div>
        <label for="lastName">Achternaam</label>
        <input id="lastName" type="text" name="lastName" required>
    </div>
    <div>
        <label for="dateOfBirth">Geboortedatum</label>
        <input id="dateOfBirth" type="text" name="dateOfBirth" required>
    </div>
    <div>
        <label for="address">Adres</label>
        <input id="address" type="text" name="address" required>
    </div>
    <div>
        <label for="postalCode">Postcode</label>
        <input id="postalCode" type="text" name="postalCode" required>
    </div>
    <div>
        <label for="city">Plaats</label>
        <input id="city" type="text" name="city" required>
    </div>
    <div>
        <input type="hidden" name="createFamily">
        <input type="submit" value="Opslaan" name="Family">
    </div>
</form>