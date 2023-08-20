<h1>Contributie overzicht</h1>

<!-- Boekjaar kiesbaar maken -->

<table>
    <thead>
        <th>Soort lid</th>
        <th>Leeftijd tot</th>
        <th>Bedrag</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach ($contributions as $contribution) { ?>
            <tr>
                <td><?php echo $contribution['Description']; ?></td>
                <td><?php echo $contribution['Age']; ?></td>
                <td><?php echo $contribution['Cost']; ?></td>
                <td>
                    <div class="button-container">
                        <form action="index.php" method="post">
                            <input type="hidden" name="ID" value="$contribution['contributionID']">
                            <input type="hidden" name="editContribution">
                            <input type="submit" value="Bewerken" name="Contribution">
                        </form>
                        <form action="index.php" method="post">
                            <input type="hidden" name="deleteContribution">
                            <input type="submit" value="Verwijderen" name="Contribution">
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>