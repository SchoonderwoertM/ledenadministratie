<h1>Familieleden</h1>

<table>
    <thead>
        <th>Naam</th>
        <th>Geboortedatum</th>
        <th>Soort lid</th>
        <th>Contributie</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach ($familyMembers as $familyMember) { ?>
            <tr>
                <td><?php echo $familyMember['Name']; ?></td>
                <td><?php echo $familyMember['DateOfBirth']; ?></td>
                <td><?php echo $familyMember['Description']; ?></td>
                <td><?php echo $familyMember['Cost']; ?></td>
                <td>
                    <div class="button-container">
                        <form action="index.php" method="post">
                            <input type="hidden" name="familyMemberID" value="<?php echo $familyMember['FamilyMemberID'] ?>">
                            <input type="hidden" name="editFamilyMember">
                            <input type="submit" value="Bewerken" name="FamilyMember">
                        </form>
                        <form action="index.php" method="post">
                            <input type="hidden" name="deleteFamilyMember">
                            <input type="submit" value="Verwijderen" name="FamilyMember">
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<form action="index.php" method="post">
    <input type="hidden" name="addFamilyMember">
    <input type="submit" value="Familielid toevoegen" name="FamilyMember">
</form>