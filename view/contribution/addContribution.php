<h1>Soort lidmaatschap toevoegen</h1>

<form action="index.php" method="post">
    <div>
        <label for="membership">Soort lid</label>
        <input id="membership" type="text" autofocus="on" required>
    </div>
    <div>
        <label for="age">Leeftijd</label>
        <input id="age" type="number" min="0" max="120" required>
    </div>
    <div>
        <label for="discount">Korting (%)</label>
        <input id="discount" type="number" min="0" max="100" required>
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