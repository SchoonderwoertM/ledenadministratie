<h1>Familielid toevoegen</h1>

<form action="index.php" method="post">
    <div>
        <label for="name">Naam</label>
        <input id="name" type="text" required>
    </div>
    <div>
        <label for="dateOfBirth">Geboortedatum</label>
        <input id="dateOfBirth" type="number" required>
    </div>
    <div>
        <label for="membership">Soort lid</label>
        <input in="membership" type="number" required>
    </div>
    <div>
        <input type="hidden" name="createFamilyMember">
        <input type="submit" value="Opslaan" name="FamilyMember">
    </div>
</form>