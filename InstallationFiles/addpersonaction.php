<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$name=$_POST["name"];

if ($_POST["address"]!="" && $_POST["licence"]!="") // Enter a slightly different query depending on which optional fields the user has filled in, so that the SQL INSERT statement contains the appropriate fields
{
    $address=$_POST["address"];
    $licence=$_POST["licence"];
    $sql="INSERT INTO People (People_name,People_address,People_licence) VALUES ('$name','$address','$licence');"; // Query to insert new person in the database
    $result=mysqli_query($conn,$sql);

}

elseif ($_POST["address"]!="")
{
    $address=$_POST["address"];
    $sql="INSERT INTO People (People_name,People_address) VALUES ('$name','$address');"; // Query to insert new person in the database
    $result=mysqli_query($conn,$sql);
}

elseif ($_POST["licence"]!="")
{ 
    $licence=$_POST["licence"];
    $sql="INSERT INTO People (People_name,People_licence) VALUES ('$name','$licence');"; // Query to insert new person in the database
    $result=mysqli_query($conn,$sql);
}
else
{
    $licence=$_POST["licence"];
    $sql="INSERT INTO People (People_name) VALUES ('$name');"; // Query to insert new person in the database
    $result=mysqli_query($conn,$sql);
}

header("Location: addperson.php?flag=1"); // Once the appropriate query has been executed, return to the addperson page and display the confirmation message that the new person has been added
?>