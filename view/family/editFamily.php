<?php require_once 'include\authenticate.php' ?>
<h1>Familie <?php echo $family->name ?> aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="familyName">Familienaam</label>
        <input id="familyName" type="text" maxlength="100" name="name" value="<?php echo $family->name ?>" autofocus="on" required>
    </div>
    <div>
        <label for="street">Straat</label>
        <input id="street" type="text" maxlength="100" name="street" value="<?php echo $family->street ?>" required>
    </div>
    <div>
        <label for="housenumber">Huisnummer</label>
        <input id="housenumber" type="number" maxlength="5" name="housenumber" value="<?php echo $family->housenumber ?>" required>
    </div>
    <div>
        <label for="postalCode">Postcode</label>
        <input id="postalCode" type="text" maxlength="7" name="postalCode" value="<?php echo $family->postalCode ?>" required>
    </div>
    <div>
        <label for="city">Plaats</label>
        <input id="city" type="text" maxlength="100" name="city" value="<?php echo $family->city ?>" required>
    </div>
    <input type="hidden" name="familyID" value="<?php echo $family->familyID ?>">
    <input type="hidden" name="updateFamily">
    <input type="submit" value="Opslaan" name="Family">
    <input type="submit" value="Annuleren" form="goBack">

</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Family">
    <input type="hidden" name="manageFamilies">
</form>