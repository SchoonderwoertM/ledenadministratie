<?php require_once 'include\authenticate.php' ?>
<h1>Familie overzicht</h1>

    <table>
        <thead>
            <th>Familie</th>
            <th>Adres</th>
            <th>Aantal leden</th>
            <th>Contributie</th>
            <th></th>
        </thead>
        <tbody>
            <?php foreach ($families as $family) { ?>
                <tr>
                    <td><?php echo $family->name; ?></td>
                    <td><?php echo $family->street . " " . $family->housenumber . " te " . $family->city; ?></td>
                    <td><?php echo $family->numberOfFamilyMembers; ?></td>
                    <td><?php echo $family->totalContribution; ?></td>
                    <td>
                        <div class="button-container">
                            <form action="index.php" method="post">
                                <input type="hidden" name="familyID" value="<?php echo $family->familyID; ?>">
                                <input type="hidden" name="manageFamilyMembers">
                                <input type="submit" value="Inzien" name="FamilyMember">
                            </form>
                            <form action="index.php" method="post">
                                <input type="hidden" name="familyID" value="<?php echo $family->familyID; ?>">
                                <input type="hidden" name="editFamily">
                                <input type="submit" value="Bewerken" name="Family">
                            </form>
                            <form action="index.php" method="post">
                                <input type="hidden" name="familyID" value="<?php echo $family->familyID; ?>">
                                <input type="hidden" name="deleteFamilyMessage">
                                <input type="submit" value="Verwijderen" name="Family">
                            </form>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<form action="index.php" method="post">
    <input type="hidden" name="addFamily">
    <input type="submit" value="Familie toevoegen" name="Family">
</form>