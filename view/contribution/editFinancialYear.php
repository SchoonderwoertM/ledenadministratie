<h1>Boekjaar aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="year">Boekjaar</label>
        <input id="year" type="number" min="0" name="year" value="<?php echo $financialYear['Year'] ?>" autofocus="on" required>
    </div>
    <div>
        <label for="contribution">Contributie</label>
        <input id="contribution" type="text" min="0" max="1000" name="cost" placeholder="€" value="<?php echo $financialYear['Cost'] ?>" required>
    </div>
    <div>
        <input type="hidden" name="financialYearID" value="<?php echo $financialYear['FinancialYearID'] ?>">
        <input type="hidden" name="updateFinancialYear">
        <input type="submit" value="Opslaan" name="Contribution">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Contribution">
    <input type="hidden" name="manageContributions">
</form>