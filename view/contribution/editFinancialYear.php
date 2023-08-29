<?php require_once 'include\authenticate.php' ?>
<h1>Boekjaar <?php echo $financialYear->year ?> aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="contribution">Contributie (€)</label>
        <input id="contribution" type="text" min="0" max="1000" name="cost" value="<?php echo $financialYear->cost ?>" autofocus="on" required>
    </div>
    <div>
        <input type="hidden" name="financialYearID" value="<?php echo $financialYear->financialYearID ?>">
        <input type="hidden" name="updateFinancialYear">
        <input type="submit" value="Opslaan" name="Contribution">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Contribution">
    <input type="hidden" name="manageContributions">
</form>