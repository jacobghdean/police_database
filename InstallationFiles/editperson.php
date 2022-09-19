<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);


if (isset($_POST["newname"]) || isset($_POST["newaddress"]) || isset($_POST["newlicence"])) // Enter this conditional if the user has entered input to the form for making changes to the row
{
    if ($_POST["newname"]!="") // Enter this conditional if the user wants to make changes to the person's name
    {
        $newname=$_POST["newname"];
        $sql = "UPDATE People SET People_name='$newname' WHERE People_ID= '".$_SESSION['id']."';"; // Query to update the row we wish to edit with the new name
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newaddress"]!="") // Enter this conditional if the user wants to make changes to the person's address
    {
        $newaddress=$_POST["newaddress"];
        $sql = "UPDATE People SET People_address='$newaddress' WHERE People_ID= '".$_SESSION['id']."';";
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newlicence"]!="")
    {
        $newlicence=$_POST["newlicence"];
        $sql = "UPDATE People SET People_licence='$newlicence' WHERE People_ID= '".$_SESSION['id']."';";
        $result=mysqli_query($conn,$sql);
    }
}

if ($_GET)
{
    $_SESSION["id"]=$_GET["flag"]; // The 'flag' number in the URL specifies the person id of the row we would like to edit - save this id number
}
?>

<html>
<head>
<title>Edit Person</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 
</head>

<header>
    <p><a href="searchperson.php?flag=3"><i>Back</i></a></p>
</header>

<section>
    <table> <!-- create a table containing the single row we would like to edit -->
    <thead>
    <tr><th>People ID</th><th>Name</th><th>Address</th><th>Licence</th></tr>
    </thead>
    <?php
    $sql = "SELECT * FROM People WHERE People_ID='".$_SESSION["id"]."';"; // select the row from the People table corresponding to the ID of the person we wish to edit
    $result=mysqli_query($conn,$sql);
    $array=mysqli_fetch_array($result);
    echo "<tr><td>$array[0]</td><td>$array[1]</td><td>$array[2]</td><td>$array[3]</td></tr>"; // insert the SQL output into the table
    echo '</table>
    </section><br/>';

    echo // Creates a form that allows the user to enter any changes they would like to make to the row
    '<section>
    <form action="editperson.php" method="post">
    <header>
    <h2>You can make changes to this person here:</h2>
    </header>
        <label for="newname">Full Name:</label>
        <input type="text" name="newname"><br/>
        <label for="newaddress">Address:</label>
        <input type="text" name="newaddress"><br/>
        <label for="newlicence">Driving Licence:</label>
        <input type="text" name="newlicence"><br/>
        <button type="submit">Submit</button>
    </form>
    </section>';

	footer();
    
	?>
</html>