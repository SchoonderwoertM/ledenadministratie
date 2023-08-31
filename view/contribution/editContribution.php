<?php require_once 'include\authenticate.php' ?>
<h1>Lidmaatschap <?php echo $contribution->membershipType; ?> aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="membership">Soort lid</label>
        <input id="membership" type="text" maxlength="50" name="description" value="<?php echo $contribution->membershipType; ?>" autofocus="on" required>
    </div>
    <div>
        <label for="age">Leeftijd tot</label>
        <input id="age" type="number" min="0" max="100" name="age" value="<?php echo $contribution->age; ?>" required>
    </div>
    <div>
        <label for="discount">Korting (%)</label>
        <input id="discount" type="number" min="0" max="100" name="discount" value="<?php echo $contribution->discount; ?>" required>
    </div>
    <div>
        <input type="hidden" name="membershipID" value="<?php echo $contribution->membershipID; ?>">
        <input type="hidden" name="contributionID" value="<?php echo $contribution->contributionID ?>">
        <input type="hidden" name="updateContribution">
        <input type="submit" value="Opslaan" name="Contribution">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="Contribution">
    <input type="hidden" name="manageContributions">
</form>
