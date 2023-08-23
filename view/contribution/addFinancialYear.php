<h1>Boekjaar toevoegen</h1>

<form action="index.php" method="post">
    <div>
        <label for="financialYear">Boekjaar</label>
        <input id="financialYear" type="number" min="2020" max="2100" step="1" placeholder="<?php echo date('Y') ?>" autofocus="on" required>
    </div>
    <div>
        <label for="contribution">Contributie</label>
        <input id="contribution" type="number" min="0" max="1000" step="1" placeholder="€" required>
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