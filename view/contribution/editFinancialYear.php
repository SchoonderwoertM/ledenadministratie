<h1>Boekjaar aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="financialYear">Boekjaar</label>
        <input id="financialYear" type="number" min="0" name="financialYear" value="<?php echo $financialYear['Year'] ?>" autofocus="on" required>
    </div>
    <div>
        <label for="contribution">Contributie</label>
        <input id="contribution" type="text" min="0" max="1000" name="contribution" placeholder="€" value="<?php echo $financialYear['Cost'] ?>" required>
    </div>
    <div>
        <input type="hidden" name="FiancialYearID" value="<?php echo $financialYear['FiancialYearID'] ?>">
        <input type="hidden" name="updateFinancialYear">
        <input type="submit" value="Opslaan" name="Contribution">
    </div>
</form>