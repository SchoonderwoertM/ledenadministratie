<h1>Contributie aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="membership">Soort lid</label>
        <input id="membership" type="text" value="<?php echo $contribution['Description'] ?>" autofocus="on" required>
    </div>
    <div>
        <label for="age">Leeftijd tot</label>
        <input id="age" type="text" min="0" max="150" value="<?php echo $contribution['Age'] ?>" required>
    </div>
    <div>
        <label for="cost">Bedrag</label>
        <input id="cost" type="text" min="0" max="999" value="<?php echo $contribution['Cost'] ?>" required>
    </div>
    <div>
        <input type="hidden" name="updateContribution">
        <input type="submit" value="Opslaan" name="Contribution">
    </div>
</form>