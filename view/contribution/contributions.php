<h1>Contributie overzicht</h1>

<h2>Boekjaren</h2>
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
                            <input type="hidden" name="contributionID" value="<?php echo $financialYear['ContributionID'] ?>">
                            <input type="hidden" name="deleteFinancialYear">
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
    <h2>Soorten lidmaatschap</h2>
    <form method="post" action="index.php">
        <label for="financialYears" class="inline">Selecteer een boekjaar</label>
        <select id="financialYears" name="financialYear">
            <?php
            foreach ($financialYears as $financialYear) {
                echo '<option value="' . $financialYear['Year'] . '">' . ucfirst($financialYear['Year']) . '</option>';
            }
            ?>
            <input type="hidden" name="year" value="<?php echo $financialYear['Year'] ?>">
            <input type="hidden" name="manageContributions">
            <input type="submit" name="Contribution" value="Bevestigen">
        </select>
    </form>

    <?php if ($contributions) { ?>
        <table>
            <thead>
                <th>Soort lid</th>
                <th>Leeftijd tot</th>
                <th>Korting</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($contributions as $contribution) { ?>
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
                                    <input type="hidden" name="membershipID" value="<?php echo $contribution['MembershipID'] ?>">
                                    <input type="hidden" name="deleteContribution">
                                    <input type="submit" value="Verwijderen" name="Contribution">
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <form action="index.php" method="post">
        <input type="hidden" name="addContribution">
        <input type="submit" value="Contriubtie toevoegen" name="Contribution">
    </form>
</div>