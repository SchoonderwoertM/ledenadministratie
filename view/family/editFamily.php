<?php require_once 'include\authenticate.php' ?>
<h1>Familie aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="familyName">Familienaam</label>
        <input id="familyName" type="text" name="name" value="<?php echo $family->Name ?>" autofocus="on" required>
    </div>
    <div>
        <label for="address">Straat en huisnummer</label>
        <input id="address" type="text" name="address" value="<?php echo $family->Address ?>" required>
    </div>
    <div>
        <label for="postalCode">Postcode</label>
        <input id="postalCode" type="text" name="postalCode" value="<?php echo $family->PostalCode ?>" required>
    </div>
    <div>
        <label for="city">Plaats</label>
        <input id="city" type="text" name="city" value="<?php echo $family->City ?>" required>
    </div>
    <input type="hidden" name="familyID" value="<?php echo $family->FamilyID ?>">
    <input type="hidden" name="updateFamily">
    <input type="submit" value="Opslaan" name="Family">
    <input type="submit" value="Annuleren" form="goBack">

</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Family">
    <input type="hidden" name="manageFamilies">
</form>