<?php require_once 'include\authenticate.php' ?>
<h1>Lidmaatschap aanpassen</h1>
<p><strong>Boekjaar <?php echo $membership->financialYear ?></strong></p><br>

<form action="index.php" method="post">
    <div>
        <label for="membership">Soort lid</label>
        <input id="membership" type="text" maxlength="50" name="description" value="<?php echo $membership->membershipType; ?>" autofocus="on" required>
    </div>
    <div>
        <label for="age">Leeftijd tot</label>
        <input id="age" type="number" min="0" max="100" name="age" value="<?php echo $membership->age; ?>" required>
    </div>
    <div>
        <label for="discount">Korting (%)</label>
        <input id="discount" type="number" min="0" max="100" name="discount" value="<?php echo $membership->discount; ?>" required>
    </div>
    <div>
        <input type="hidden" name="membershipID" value="<?php echo $membership->membershipID; ?>">
        <input type="hidden" name="contributionID" value="<?php echo $membership->contributionID ?>">
        <input type="hidden" name="updateMembership">
        <input type="submit" value="Opslaan" name="Contribution">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Contribution">
    <input type="hidden" name="manageContributions">
</form>