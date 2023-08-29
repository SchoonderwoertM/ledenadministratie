<?php require_once 'include\authenticate.php' ?>
<h1>Familielid <?php echo $familyMember->name ?> wijzigen</h1>

<form action="index.php" method="post">
    <div>
        <label for="name">Naam</label>
        <input id="name" type="text" name="name" value="<?php echo $familyMember->name ?>" autofocus="on" required>
    </div>
    <div>
        <label for="dateOfBirth">Geboortedatum</label>
        <input id="dateOfBirth" type="date" name="dateOfBirth" value="<?php echo $familyMember->dateOfBirth ?>" required>
    </div>
    <div>
        <input type="hidden" name="familyID" value="<?php echo $familyMember->familyID ?>">
        <input type="hidden" name="familyMemberID" value="<?php echo $familyMember->familyMemberID ?>">
        <input type="hidden" name="updateFamilyMember">
        <input type="submit" value="Opslaan" name="FamilyMember">
        <input type="submit" value="Annuleren" form="goBack">
    </div>
</form>
<form id="goBack" action="index.php" method="post">
    <input type="hidden" name="familyID" value="<?php echo $familyMember->familyID ?>">
    <input type="hidden" name="FamilyMember">
    <input type="hidden" name="manageFamilyMembers">
</form>