<?php require_once 'include\authenticate.php' ?>

<p>Bij het verwijderen van de familie worden ook alle familieleden verwijderd.
<p>Weet u zeker dat u de familie wilt verwijderen?</p>

<form action="index.php" method="post">
    <input type="hidden" name="familyID" value="<?php echo $familyID ?>">
    <input type="hidden" name="deleteFamily">
    <input type="submit" value="Verwijderen" name="Family">
    <input type="submit" value="Annuleren" form="goBack">
</form>

<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Family">
    <input type="hidden" name="manageFamilies">
</form>