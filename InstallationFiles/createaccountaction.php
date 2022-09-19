<?php
require __DIR__ . '/functions.php';
verify($admin=True); // We specify the 'admin' parameter as True here to ensure that non-admins cannot access this feature
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$newuser=$_POST["user"];
$newpass=$_POST["pass"];

$sql = "INSERT INTO Users (Username,Password) VALUES ('$newuser', '$newpass');"; // Query to insert the new police officer account
$result=mysqli_query($conn,$sql);

header("Location: createaccount.php?flag=1"); // Return to the createaccount page with a flag in the URL after this page has finished running

?>