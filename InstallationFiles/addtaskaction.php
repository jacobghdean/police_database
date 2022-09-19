<?php
require __DIR__ . '/functions.php';
verify($admin=True); // We specify the 'admin' parameter as True here to ensure that non-admins cannot access this feature
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$description=$_POST["description"];
$officer=$_POST["officer"];

$sql="SELECT UserID FROM Users WHERE Username='$officer';"; // Find the ID of the police officer that the task has been assigned to
$result=mysqli_query($conn,$sql);
$index=mysqli_fetch_array($result)[0];
$sql = "INSERT INTO Tasks (Task_Description,UserID) VALUES ('$description', '$index');"; // Query to insert the new task information
$result=mysqli_query($conn,$sql);

header("Location: addtask.php?flag=1"); // Redirect back to the addtask page with a flag in the URL when this file has fully executed

?>