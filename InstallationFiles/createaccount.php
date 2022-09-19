<?php
require __DIR__ . '/functions.php'; //Reference: https://stackoverflow.com/questions/8104998/how-to-call-function-of-one-php-file-from-another-php-file-and-pass-parameters-t/31890917
verify($admin=True); // We specify the 'admin' parameter as True here to ensure that non-admins cannot access this feature
menu();
?>

<html>
<head>
<title>Create Account</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css">

<script>
function ask() // Validation to ensure that new account has a long enough password and asks for confirmation before the new account is created
{
    var x = document.forms["newaccount"]["pass"].value;

    if (x.length<5)
    {
        alert("Password must be at least 5 characters long!");
        return false;
    }
    
    text = "Would you like to make these changes?\nSelect OK or Cancel.";

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

    <!-- Form for creating a new police account - username and password are required fields -->
    <form name="newaccount" onsubmit="return ask()" action="createaccountaction.php" method="post">

    <header>
        <h2>Create New Police Account</h2>
    </header>
    <label for="user">Username:*</label>
    <input type="text" name="user" required><br/>
    <label for="pass">Password:*</label>
    <input type="password" name="pass" required><br/>
    <i>* Required Field</i><br/><br/>
    <button type="submit">Submit</button>

    </form>

</section>

<?php
if ($_GET) // If there is a flag in the URL this means that the new policeman has been successfully added so we print a validation message
{
    echo "<p>New Policeman added</p>";
}
?>

</html>