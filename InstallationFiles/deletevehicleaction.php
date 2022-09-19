<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$id=$_GET["flag"]; // Store the row we wish to delete as a new variable
$sql = "DELETE FROM Vehicle WHERE Vehicle_ID=$id;";  // Used ON DELETE CASCADE and ON DELTE SET NULL in the SQL file in order to delete all ownership relating to that vehicle but retain the incident information
$result=mysqli_query($conn,$sql);
header("Location: searchvehicle.php?flag=3"); // Redirect back to the search vehicle page after the row has been deleted
?>