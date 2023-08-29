<?php require_once 'include\authenticate.php' ?>

<p>Bij het verwijderen van het boekjaar worden ook alle lidmaatschappen die gekoppeld zijn aan het boekjaar verwijderd.
<p>Weet u zeker dat u het boekjaar wilt verwijderen?</p>

<form action="index.php" method="post">
    <input type="hidden" name="financialYearID" value="<?php echo $financialYearID ?>">
    <input type="hidden" name="deleteFinancialYear">
    <input type="submit" value="Verwijderen" name="Contribution">
    <input type="submit" value="Annuleren" form="goBack">
</form>

<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Contribution">
    <input type="hidden" name="manageContributions">
</form>