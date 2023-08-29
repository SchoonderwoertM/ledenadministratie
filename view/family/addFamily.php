<?php require_once 'include\authenticate.php' ?>
<h1>Familie toevoegen</h1>
<p>De familie wordt toegevoegd door hieronder het eerste familielid te registreren.</p></br>

<form action="index.php" method="post">
    <div>
        <label for="firstName">Voornaam</label>
        <input id="firstName" type="text" maxlength="50" name="firstName" autofocus="on" required>
    </div>
    <div>
        <label for="lastName">Achternaam</label>
        <input id="lastName" type="text" maxlength="100" name="lastName" required>
    </div>
    <div>
        <label for="dateOfBirth">Geboortedatum</label>
        <input id="dateOfBirth" type="date" name="dateOfBirth" required>
    </div>
    <div>
        <label for="street">Straat</label>
        <input id="street" type="text" maxlength="100" name="street" required>
        <label for="housenumber">Huisnummer</label>
        <input id="housenumber" type="text" maxlength="5" name="housenumber" required>
    </div>
    <div>
        <label for="postalCode">Postcode</label>
        <input id="postalCode" type="text" maxlength="7" name="postalCode" required>
    </div>
    <div>
        <label for="city">Plaats</label>
        <input id="city" type="text" maxlength="100" name="city" required>
    </div>
    <div>
        <input type="hidden" name="createFamily">
        <input type="submit" value="Opslaan" name="Family">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Family">
    <input type="hidden" name="manageFamilies">
</form>