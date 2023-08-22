<h1>Boekjaar toevoegen</h1>

<formaction="index.php" method="post">
    <div>
        <label for="financialYear">Boekjaar</label>
        <input id="financialYear" type="number" min="2020" max="2100" step="1" placeholder="<?php echo date('Y') ?>"  autofocus="on" required>
    </div>
    <div>
        <label for="contribution">Contributie</label>
        <input id="contribution" type="number" min="0" max="1000" step="1" placeholder="€" required>
    </div>
    <div>
        <input type="hidden" name="createContribution">
        <input type="submit" value="Opslaan" name="Contribution">
    </div>
    </form>