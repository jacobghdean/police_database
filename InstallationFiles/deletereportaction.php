<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$id=$_GET["flag"]; // Store the row we wish to delete as a new variable
$sql = "DELETE FROM Incident WHERE Incident_ID=$id;";
$result=mysqli_query($conn,$sql);
header("Location: searchreport.php?flag=3"); // Redirect back to the search incident page after the row has been deleted
?>