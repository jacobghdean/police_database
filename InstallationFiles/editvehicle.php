<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);


if (isset($_POST["newtype"]) || isset($_POST["newcolour"]) || isset($_POST["newplate"]) || isset($_POST["newlicence"])) // Enter this conditional if the user has entered input to the form for making changes to the row
{
    if ($_POST["newtype"]!="") // Enter this conditional if the user wants to make changes to the vehicle's type
    {
        $newtype=$_POST["newtype"];
        $sql = "UPDATE Vehicle SET Vehicle_type='$newtype' WHERE Vehicle_ID= '".$_SESSION['id']."';"; // Query to update the row we wish to edit with the new type
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newcolour"]!="") // Enter this conditional if the user wants to make changes to the vehicle's colour
    {
        $newcolour=$_POST["newcolour"];
        $sql = "UPDATE Vehicle SET Vehicle_colour='$newcolour' WHERE Vehicle_ID= '".$_SESSION['id']."';";
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newplate"]!="")
    {
        $newplate=$_POST["newplate"];
        $sql = "UPDATE Vehicle SET Vehicle_licence='$newplate' WHERE Vehicle_ID= '".$_SESSION['id']."';";
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newlicence"]!="") // This is an extra conditional for if the user wants to change the owner assigned to the vehicle
    {
        $newlicence=$_POST["newlicence"];
        $sql = "SELECT People_ID FROM People WHERE People_licence='$newlicence';"; // Convert the driving licence into a person id
        $result=mysqli_query($conn,$sql);
        $new_id=mysqli_fetch_array($result)[0];
        $sql1="SELECT * FROM Ownership WHERE Vehicle_ID= ".$_SESSION['id'].";"; // Each vehicle should only have one owner so we don't need to check for owner id as well
        $result1=mysqli_query($conn,$sql1);
        if (mysqli_num_rows($result1)>0) // Evaluates True when an owenrship relation with the chosen vehicle already exists
        {
            $sql = "UPDATE Ownership SET People_ID=$new_id WHERE Vehicle_ID= ".$_SESSION['id'].";"; // Reset the owner of the vehicle with the new owner
            $result=mysqli_query($conn,$sql);
        }
        else // Evaluates True when an ownership relation with the chosen vehicle does not exist
        {
            $sql = "INSERT INTO Ownership (People_ID,Vehicle_ID) VALUES ($new_id,".$_SESSION['id'].");"; // Add a new ownership relation between the vehicle and the new owner
            $result=mysqli_query($conn,$sql);
        }
    }
}

if ($_GET)
{
    $_SESSION["id"]=$_GET["flag"]; // The 'flag' number in the URL specifies the person id of the row we would like to edit - save this id number
}
?>

<html>
<head>
<title>Edit Vehicle</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 
</head>

<header>
    <p><a href="searchvehicle.php?flag=3"><i>Back</i></a></p>
</header>

    <section>
    <table> <!-- create a table containing the single row we would like to edit -->
    <thead>
    <tr><th>Type</th><th>Colour</th><th>Licence Plate</th><th>Owner Name</th><th>Owner Licence</th></tr>
    </thead>
    <?php
    // select the row from the Vehicle table corresponding to the ID of the vehicle we wish to edit
    $sql="SELECT Vehicle_type, Vehicle_colour, Vehicle_licence, People_name, People_licence
    FROM Vehicle LEFT JOIN (SELECT People_name, People_licence, Vehicle_ID FROM Ownership, People 
    WHERE Ownership.People_ID=People.People_ID) AS S ON Vehicle.Vehicle_ID=S.Vehicle_ID WHERE Vehicle.Vehicle_ID='".$_SESSION["id"]."';";
    $result=mysqli_query($conn,$sql);
    $array=mysqli_fetch_array($result);
    echo "<tr><td>$array[0]</td><td>$array[1]</td><td>$array[2]</td><td>$array[3]</td><td>$array[4]</td></tr>"; // insert the SQL output into the table
    // For some reason, mysqli_fetch_array fails if you call it more than once (you can only fetch your array once), so you need to subdivide the case where num rows is 1 and num rows is more than 1 in order to fill in the table and also record the incident id global variable
    echo '</table>
    </section><br/>';

    echo // Creates a form that allows the user to enter any changes they would like to make to the row
    '<section>
    <form action="editvehicle.php" method="post">
    <header>
    <h2>You can make changes to this vehicle here:</h2>
    </header>
        <label for="newtype">Type:</label>
        <input type="text" name="newtype"><br/>
        <label for="newcolour">Colour:</label>
        <input type="text" name="newcolour"><br/>
        <label for="newplate">Licence Plate:</label>
        <input type="text" name="newplate"><br/>
        <label for="newlicence">Owner Licence:</label>
        <input type="text" name="newlicence"><br/>
        <button type="submit">Submit</button>
    </form>
    </section>';
    

  
	footer();
	?>
</html>