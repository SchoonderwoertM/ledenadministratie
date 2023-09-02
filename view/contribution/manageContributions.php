<?php require_once 'include\authenticate.php' ?>
<h1>Contributie overzicht</h1>

<table>
    <thead>
        <th>Boekjaar</th>
        <th>Contributie (€)</th>
        <th></th>
    </thead>
    <tbody>
        <?php

        foreach ($financialYears as $financialYear) { ?>
            <tr>
                <td><?php echo $financialYear->year; ?></td>
                <td><?php echo $financialYear->contribution; ?></td>
                <td>
                    <div class="button-container">
                        <form action="index.php" method="post">
                            <input type="hidden" name="financialYearID" value="<?php echo $financialYear->financialYearID ?>">
                            <input type="hidden" name="editFinancialYear">
                            <input type="submit" value="Bewerken" name="Contribution">
                        </form>
                        <form action="index.php" method="post">
                            <input type="hidden" name="financialYearID" value="<?php echo $financialYear->financialYearID ?>">
                            <input type="hidden" name="deleteFinancialYearMessage">
                            <input type="submit" value="Verwijderen" name="Contribution">
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<form action="index.php" method="post">
    <input type="hidden" name="addFinancialYear">
    <input type="submit" value="Boekjaar toevoegen" name="Contribution">
</form>

<div class="marginTop">
    <h2>Lidmaatschappen</h2>
    <p>Let op! Na het aanpassen van de lidmaatschappen dient u op de knop 'Ververs lidmaatschappen' te klikken om de lidmaatschappen van de bestaande leden bij te werken.</p><br>
    <form method="post" action="index.php">
        <label for="financialYear">Selecteer een boekjaar</label>
        <select id="financialYear" name="financialYear">
            <option>-</option>
            <?php foreach ($financialYears as $financialYear) {
                $selected = ($financialYear->year == $_POST['financialYear']) ? 'selected' : '';
                echo "<option value='$financialYear->year' $selected>$financialYear->year</option>";
            } ?>
        </select>
        <input type="hidden" name="manageContributions">
        <input type="submit" value="Bevestigen" name="Contribution">
    </form>
    <?php if ($memberships) { ?>
        <table>
            <thead>
                <th>Soort lid</th>
                <th>Leeftijd tot</th>
                <th>Korting</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($memberships as $membership) { ?>
                    <tr>
                        <td><?php echo $membership->membershipType; ?></td>
                        <td><?php echo $membership->age; ?></td>
                        <td><?php echo $membership->discount; ?></td>
                        <td>
                            <div class="button-container">
                                <form action="index.php" method="post">
                                    <input type="hidden" name="contributionID" value="<?php echo $membership->contributionID ?>">
                                    <input type="hidden" name="editMembership">
                                    <input type="submit" value="Bewerken" name="Contribution">
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <form action="index.php" method="post">
        <input type="hidden" name="recalculateMemberships">
        <input type="submit" value="Ververs lidmaatschappen" name="Contribution">
    </form>
</div>