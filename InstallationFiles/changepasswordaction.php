<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$newpassword=$_POST["new"]; // Store the requested new password 
$sql = "UPDATE Users SET Password='$newpassword' WHERE Username='".$_SESSION["Person"]."';"; // Query to update password for the row with the appropriate username
mysqli_query($conn,$sql);			
header("Location: changepassword.php?flag=1"); // Redirect back to the change password page once the update has completed and display a validation message
?>
