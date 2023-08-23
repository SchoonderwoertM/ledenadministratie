<h1>Contributie overzicht</h1>

<table>
    <thead>
        <th>Boekjaar</th>
        <th>Contributie</th>
        <th></th>
    </thead>
    <tbody>
        <?php

        foreach ($financialYears as $financialYear) { ?>
            <tr>
                <td><?php echo $financialYear['Year']; ?></td>
                <td><?php echo $financialYear['Cost']; ?></td>
                <td>
                    <div class="button-container">
                        <form action="index.php" method="post">
                            <input type="hidden" name="financialYearID" value="<?php echo $financialYear['FinancialYearID'] ?>">
                            <input type="hidden" name="editFinancialYear">
                            <input type="submit" value="Bewerken" name="Contribution">
                        </form>
                        <form action="index.php" method="post">
                            <input type="hidden" name="financialYearID" value="<?php echo $financialYear['FinancialYearID'] ?>">
                            <input type="hidden" name="deleteFinancialYear">
                            <input type="submit" value="Verwijderen" name="Contribution">
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<form method="post" action="index.php">
    <label for="financialYears">Selecteer een boekjaar</label>
    <select name="financialYears" id="financialYears">
        <option value="">-- Selecteer een boekjaar --</option>
        <?php
        foreach ($financialYears as $financialYear) {
            $year = $financialYear['Year'];
            echo "<option value='$year'>$year</option>";
        }
        ?>
        <input type="hidden" name="financialYearID" value="<?php echo $financialYear['FinancialYearID'] ?>">
        <input type="hidden" name="manageContributions">
        <input type="submit" name="Contribution" value="Bevestigen">
    </select>
</form>

<?php if (!empty($contributions)) { ?>
    <table>
        <thead>
            <th>Soort lid</th>
            <th>Leeftijd tot</th>
            <th>Korting</th>
            <th></th>
        </thead>
        <tbody>
            <?php

            foreach ($contributions as $contribution) { ?>
                <tr>
                    <td><?php echo $contribution['Description']; ?></td>
                    <td><?php echo $contribution['Age']; ?></td>
                    <td><?php echo $contribution['Discount']; ?></td>
                    <td>
                        <div class="button-container">
                            <form action="index.php" method="post">
                                <input type="hidden" name="contributionID" value="<?php echo $contribution['ContributionID'] ?>">
                                <input type="hidden" name="editContribution">
                                <input type="submit" value="Bewerken" name="Contribution">
                            </form>
                            <form action="index.php" method="post">
                                <input type="hidden" name="contributionID" value="<?php echo $contribution['ContributionID'] ?>">
                                <input type="hidden" name="deleteContribution">
                                <input type="submit" value="Verwijderen" name="Contribution">
                            </form>
                        </div>
                    </td>
                </tr>
        <?php }
        } ?>
        </tbody>
    </table>

    <div class="button-container">
        <form action="index.php" method="post">
            <input type="hidden" name="addFinancialYear">
            <input type="submit" value="Boekjaar toevoegen" name="Contribution">
        </form>
        <form action="index.php" method="post">
            <input type="hidden" name="addContribution">
            <input type="submit" value="Contriubtie toevoegen" name="Contribution">
        </form>
    </div>