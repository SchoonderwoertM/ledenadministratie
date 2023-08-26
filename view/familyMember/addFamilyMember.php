<?php require_once 'include\authenticate.php' ?>
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
        <input type="hidden" name="familyID" value="<?php echo $familyID ?>">
        <input type="hidden" name="createFamilyMember">
        <input type="submit" value="Opslaan" name="FamilyMember">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="FamilyMember">
    <input type="hidden" name="manageFamilyMembers">
</form>