<?php require_once 'include\authenticate.php' ?>

<p>Goedendag <?php echo $_SESSION['username']; ?>! </p>
<h1>Dashboard huidig jaar</h1>

<table class="dashboardTable">
    <thead>
        <th>Familie</th>
        <th>Woonplaats</th>
        <th>Aantal leden</th>
        <th>Contributie (€)</th>
    </thead>
    <tbody>
        <?php foreach ($families as $family) { ?>
            <tr>
                <td><?php echo $family->name; ?></td>
                <td><?php echo $family->city; ?></td>
                <td><?php echo $family->numberOfFamilyMembers; ?></td>
                <td><?php echo $family->totalContribution; ?></td>
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
        <input type="submit" value="Contributies beheren" name="Contribution">
    </form>
</div>