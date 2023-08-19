<h1>Contributie overzicht</h1>

<table>
    <thead>
        <th>Boekjaar</th>
        <th>Contributie</th>
    </thead>
    <tbody>
        <?php foreach ($contributions as $contribution) { ?>
            <tr>
                <td><?php echo $contribution['Year']; ?></td>
                <td><?php echo $contribution['Cost']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<form method="post" action="index.php">
    <input type="hidden" name="addContribution">
    <input type="submit" value="Boekjaar toevoegen" name="Contribution">
</form>