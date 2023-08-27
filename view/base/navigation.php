    <?php require_once 'include\authenticate.php' ?>

    <nav>
        <form method="post" action="index.php">
            <input type="submit" value="Dashboard" />
        </form>

        <form method="post" action="index.php">
            <input type="hidden" name="manageFamilies">
            <input type="submit" value="Families" name="Family" />
        </form>

        <form method="post" action="index.php">
            <input type="hidden" name="manageContributions">
            <input type="submit" value="Contributie" name="Contribution" />
        </form>
    </nav>
    <main>