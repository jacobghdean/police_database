<?php
require __DIR__ . '/functions.php';
verify();
menu();
?>

<html>
<head>
<title>Add Person</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css">

<script>
function ask() // Validation to confirm that user would like to add the new person
{
    text = "Would you like to add this person\nSelect OK or Cancel.";
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
<header>
<p><a href="searchperson.php"><i>Search Person</i></a></p> <!-- This button will take the user to the 'search person' page, creating an easy way of moving back and forth between the searching and adding menus -->
</header>
<?php
    echo // Create a form where the user can add a new offending person to the People table, since 'People_name' has a NOT NULL requirement in the database, it is a required field in this form
    // upon submission of the form, the page is redirected to the addpersonaction page where the SQL runs to add the new person to the database
    '<section>
    <form onsubmit="return ask()" action="addpersonaction.php" method="post">
    <header>
    <h2>Add Person</h2>
    </header>
    <label for="name">Owner Name:*</label>
    <input type="text" name="name" required><br/>
    <label for="address">Owner Address:</label>
    <input type="text" name="address"><br/>
    <label for="licence">Driving Licence:</label>
    <input type="text" name="licence"><br/>
    <i>* Required Field</i><br/><br/>
    <button type="submit">Submit</button>
  
    </form>
    </section>';

    if ($_GET) // If a flag is in the URL this means that the person has been successfully added so show a validation message
    {
        echo "<p>Person added</p>";
    }

	footer();
?>
</html>
