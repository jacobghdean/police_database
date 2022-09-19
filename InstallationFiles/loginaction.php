<?php
session_start();

$_SESSION["servername"]="mysql.cs.nott.ac.uk"; // Specifies the details for connecting to the SQL database
$_SESSION["username"]="psxjd13"; // Saved under session variables so the details can be easily accessed in all other pages
$_SESSION["password"]="DOPUKU";
$_SESSION["dbname"]="psxjd13";

$conn=mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]); // Establishes connection with the SQL database

$formname=$_POST["user"]; // Collects username and password from login form
$formpassword=$_POST["pass"];

$sql = "SELECT * FROM Users WHERE BINARY Username = '$formname' AND BINARY Password= '$formpassword';"; // Returns all user accounts that match the specified username and password (the BINARY operator ensures that case sensitivity is checked)
$result = mysqli_query($conn,$sql);

$_SESSION["Check"]= mysqli_num_rows($result); // Counts number of rows from the SQL query
$_SESSION["Person"]	=$formname;
$_SESSION["Permissions"]=mysqli_fetch_array($result)[3];


if ($_SESSION["Check"]==0) // If the number of rows from the query is zero, then the user has not logged in
{
    header("Location: login.php?flag=1"); // Return user to login page if their login is unsuccessful
}
else
{
    header("Location: home.php"); // Take the user to the home page if their login is successful
}


?>
