<?php require_once 'include\authenticate.php' ?>
<h1>Familie toevoegen</h1>
<p>De familie wordt toegevoegd door hieronder het eerste familielid te registreren.</p></br>

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
        <input id="dateOfBirth" type="date" name="dateOfBirth" required>
    </div>
    <div>
        <label for="street">Straat</label>
        <input id="street" type="text" name="street" required>
        <label for="housenumber">Huisnummer</label>
        <input id="housenumber" type="text" name="housenumber" required>
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
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Family">
    <input type="hidden" name="manageFamilies">
</form>