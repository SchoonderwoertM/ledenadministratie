<h1>Familie overzicht</h1>

<table>
    <thead>
        <th>Families</th>
        <th>Street</th>
        <th>Aantal leden</th>
        <th>Contributie</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach ($families as $family) { ?>
            <tr>
                <td><?php echo $family['Name']; ?></td>
                <td><?php echo $family['Street']; ?></td>
                <td><?php echo $family['NumberOfFamilyMembers']; ?></td>
                <td></td>
                <td>
                    <div class="button-container">
                        <form action="index.php" method="post">
                            <input type="hidden" name="manageFamilyMembers">
                            <input type="submit" value="Inzien" name="FamilyMembers">
                        </form>
                        <form action="index.php" method="post">
                            <input type="hidden" name="editFamily">
                            <input type="submit" value="Bewerken" name="Family">
                        </form>
                        <form action="index.php" method="post">
                            <input type="hidden" name="deleteFamily">
                            <input type="submit" value="Verwijderen" name="Family">
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>