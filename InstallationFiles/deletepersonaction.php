<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$id=$_GET["flag"]; // Store the row we wish to delete as a new variable
// SQL query to delete the specified row
$sql = "DELETE FROM People WHERE People_ID=$id;"; // Used ON DELETE CASCADE and ON DELTE SET NULL in the SQL file in order to delete all ownership relating to that person but retain the incident information
$result=mysqli_query($conn,$sql);
header("Location: searchperson.php?flag=3"); // Redirect back to the search person page after the row has been deleted
?>
