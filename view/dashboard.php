<h1>Dashboard</h1>

<table>
    <thead>
        <th>Families</th>
    </thead>
    <tbody>
        <?php foreach ($families as $family) { ?>
            <tr>
                <td><?php echo $family['Name']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div class="button-container">
    <form action="index.php" method="post">
        <input type="hidden" name="manageFamilies">
        <input type="submit" value="Families beheren" name="Family">
    </form>
    <form action="index.php" method="post">
        <input type="hidden" name="manageContributions">
        <input type="submit" value="Contributie beheren" name="Contribution">
    </form>
</div>