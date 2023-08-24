<h1>Familielid wijzigen</h1>

<form action="index.php" method="post">
    <div>
        <label for="name">Naam</label>
        <input id="name" type="text" name="name" value="<?php echo $familyMember['Name'] ?>" autofocus="on" required>
    </div>
    <div>
        <label for="dateOfBirth">Geboortedatum</label>
        <input id="dateOfBirth" type="date" name="dateOfBirth" value="<?php echo $familyMember['DateOfBirth'] ?>" required>
    </div>
    <div>
        <input type="hidden" name="familyID" value="<?php echo $familyMember['FamilyID'] ?>">
        <input type="hidden" name="familyMemberID" value="<?php echo $familyMember['FamilyMemberID'] ?>">
        <input type="hidden" name="updateFamilyMember">
        <input type="submit" value="Opslaan" name="FamilyMember">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="familyID" value="<?php echo $familyMember['FamilyID'] ?>">
    <input type="hidden" name="FamilyMember">
    <input type="hidden" name="manageFamilyMembers">
</form>