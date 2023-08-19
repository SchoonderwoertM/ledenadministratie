<h1>Boekjaar toevoegen</h1>

<form id="contribution-form" action="index.php" method="post">
    <label>Boekjaar:</label>
    <input type="number" min="2020" max="2100" step="1" placeholder="<?php echo date('Y') ?>" required>
    <label>Contributie:</label>
    <input type="number" min="0" max="1000" step="1" placeholder="€" required>
    <input type="hidden" name="createContribution">
    <input type="submit" value="Opslaan" name="Contribution">
</form>
