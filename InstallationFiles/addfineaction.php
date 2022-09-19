<?php
require __DIR__ . '/functions.php';
verify($admin=True); // We specify the 'admin' parameter as True here to ensure that non-admins cannot access this feature
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);
            
$amount=$_POST["Amount"]; // Storing the POST outputs from the form as new variables
$points=$_POST["Points"];
$fine_id=$_POST["Fine_ID"];               

$sql = "INSERT INTO Fines (Fine_Amount,Fine_Points,Incident_ID) VALUES ('$amount', '$points', '$fine_id');"; // Inserting the new fine into the Fines table
$result=mysqli_query($conn,$sql);
header("Location: addfine.php?flag=1"); // Redirect back to the addfine page with a flag in the URL (so that the validation message is displayed)

?>