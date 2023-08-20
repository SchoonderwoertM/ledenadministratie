<form id="updateContribution-form" action="index.php" method="post">
    <label>Soort lid:</label>
    <input type="text" value="$contribution.Description" required>
    <label>Leeftijd tot:</label>
    <input type="text" value="$contribution.Age" required>
    <label>Bedrag:</label>
    <input type="text" value="$contribution.Cost" required>
    <input type="hidden" name="updateContribution">
    <input type="submit" value="Opslaan" name="Contribution">
</form>