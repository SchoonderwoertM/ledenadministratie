<h1>Familie aanpassen</h1>

<form action="index.php" method="post">
    <div>
        <label for="familyName">Familienaam</label>
        <input id="familyName" type="text" required>
    </div>
    <div>
        <label for="street">Straat</label>
        <input id="street" type="text" required>
    </div>
    <input type="hidden" name="updateFamily">
    <input type="submit" value="Opslaan" name="Family">
</form>