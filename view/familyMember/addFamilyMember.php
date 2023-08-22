<h1>Familielid toevoegen</h1>

<form action="index.php" method="post">
    <div>
        <label for="name">Naam</label>
        <input id="name" type="text" name="name" autofocus="on" required>
    </div>
    <div>
        <label for="dateOfBirth">Geboortedatum</label>
        <input id="dateOfBirth" type="date" name="dateOfBirth" required>
    </div>
    <div>
        <input type="hidden" name="familyID" value="">
        <input type="hidden" name="createFamilyMember">
        <input type="submit" value="Opslaan" name="FamilyMember">
    </div>
</form>