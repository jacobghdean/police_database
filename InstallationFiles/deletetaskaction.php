<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

$id=$_GET["flag"];
$sql = "DELETE FROM Tasks WHERE TaskID=$id;";
$result=mysqli_query($conn,$sql);
header("Location: home.php?");
?>