<?php
require __DIR__ . '/functions.php';
verify($admin=True); // We specify the 'admin' parameter as True here to ensure that non-admins cannot access this feature
menu();
?>

<html>
<head>
    <title>Add Fine</title>
    <style>html{visibility: hidden;opacity:0;}</style>
    <link rel="stylesheet" href="mvp.css"> 

    <script>
    function ask() // Ask for confirmation before adding new fine
    {
        text = "Would you like to add this fine?\nSelect OK or Cancel."; // Reference: https://www.w3schools.com/jsref/met_win_confirm.asp
        if (confirm(text) == true) 
        {
            pass
        } 
        else 
        {
            return false
        }
    }
    </script>
</head>

    <section>
        <!-- form for adding a new fine -->
        <form onsubmit="return ask()" action="addfineaction.php" method="post">
        <header>
            <h2>Add Fine</h2>
        </header>
        <label for="Amount">Fine Amount:*</label>
        <input type="text" name="Amount" required><br/>
        <label for="Points">Fine Points:*</label>
        <input type="text" name="Points" required><br/>
        <label for="Fine_ID">Incident ID:*</label>
        <input type="text" name="Fine_ID" required><br/>
        <i>*Required Field</i><br/><br/>
        <button type="submit">Submit</button>
        </form>
    </section>

    <?php
    if ($_GET) // When we redirect to this page with a flag, show a validation message that the fine has been added
    {
        echo "<p>New Fine Added</p>";
    }
    ?>
</html>
