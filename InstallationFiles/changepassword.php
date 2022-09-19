<?php
require __DIR__ . '/functions.php';
verify();
menu();
?>

<html>

<head>
<title>Change Password</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 

<script>
function ask() // Validation function that checks the length of your new password and asks for your confirmation before changing your password
{
    var x = document.forms["change"]["new"].value;

    if (x.length<5) // If the new password is smaller than 5 characters then show an error message and do not submit the form
        {
            alert("Your password must be at least 5 characters long!");
            return false;
        }

    text = "Would you like to confirm your password change\nSelect OK or Cancel."; // Reference: https://www.w3schools.com/jsref/met_win_confirm.asp
    if (confirm(text) == true) // Waits for your confirmation and only submits the new password if you click 'OK'
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

<section> <!-- form for changing the password -->
    <form name="change" onsubmit="return ask()" action="changepasswordaction.php" method="post"> <!-- upon completion of the form, redirect and post the output to changepasswordaction.php -->
    <header>
        <h2>Change Password</h2>
    </header>
        <label for="person">Change Password:*</label>
        <input type="password" name="new" required> <!-- You have to fill in the password field -->
        <i>* Required Field</i><br/><br/>
        <button type="submit">Submit</button>

	</form>
</section>
    
<?php
if ($_GET) // A flag in the URL will detect when the password has been successfully changed
{
    echo "<p>Password successfully reset</p>";
}
?>
    
</html>
