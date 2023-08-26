<?php require_once 'include\authenticate.php' ?>
<h1>Soort lidmaatschap toevoegen</h1>

<form action="index.php" method="post">
    <div>
        <label for="year">Boekjaar</label>
        <input id="year" type="number" name="financialYear" required>
    </div>
    <div>
        <label for="membership">Soort lid</label>
        <input id="membership" type="text" name="description" autofocus="on" required>
    </div>
    <div>
        <label for="age">Leeftijd tot</label>
        <input id="age" type="number" name="age" min="0" max="120" required>
    </div>
    <div>
        <label for="discount">Korting (%)</label>
        <input id="discount" type="number" name="discount" min="0" max="100" required>
    </div>
    <div>
        <input type="hidden" name="createContribution">
        <input type="submit" value="Opslaan" name="Contribution">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Contribution">
    <input type="hidden" name="manageContributions">
</form>