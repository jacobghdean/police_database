<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);


if (isset($_POST["newdate"]) || isset($_POST["newreport"]) || isset($_POST["newvehicle"])|| isset($_POST["newperson"]) || isset($_POST["newoffence"])) // Enter this conditional if the user has entered input to the form for making changes to the row
{
    if ($_POST["newdate"]!="") // Enter this conditional if the user wants to make changes to the incident's date
    {
        $newdate=$_POST["newdate"];
        $sql = "UPDATE Incident SET Incident_DATE='$newdate' WHERE Incident_ID= '".$_SESSION['id']."';"; // Query to update the row we wish to edit with the new date
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newreport"]!="") // Enter this conditional if the user wants to make changes to the incident report
    {
        $newreport=$_POST["newreport"];
        $sql = "UPDATE Incident SET Incident_Report='$newreport' WHERE Incident_ID= '".$_SESSION['id']."';";
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newvehicle"]!="")
    {
        $newvehicle=$_POST["newvehicle"];
        $sql = "SELECT Vehicle_ID FROM Vehicle WHERE Vehicle_licence='$newvehicle';"; // If we wish to update the vehicle ID, since the user enters a licence plate, this must be first converted to an ID     
        $result=mysqli_query($conn,$sql);
        $vehicle_id=mysqli_fetch_array($result)[0];
        $sql = "UPDATE Incident SET Vehicle_ID='$vehicle_id' WHERE Incident_ID= '".$_SESSION['id']."';"; // Use the vehicle ID we found when updating the incident table
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newperson"]!="")
    {
        $newperson=$_POST["newperson"];
        $sql = "SELECT People_ID FROM People WHERE People_licence='$newperson';"; // If we wish to update the person ID, since the user enters a licence number, this must be first converted to an ID    
        $result=mysqli_query($conn,$sql);
        $person_id=mysqli_fetch_array($result)[0];
        $sql = "UPDATE Incident SET People_ID='$person_id' WHERE Incident_ID= '".$_SESSION['id']."';";
        $result=mysqli_query($conn,$sql);
    }
    if ($_POST["newoffence"]!="")
    {
        $newoffence=$_POST["newoffence"];
        $sql = "UPDATE Incident SET Offence_ID='$newoffence' WHERE Incident_ID= '".$_SESSION['id']."';";
        $result=mysqli_query($conn,$sql);
    }
}
if ($_GET)
{
    $_SESSION["id"]=$_GET["flag"]; // The 'flag' number in the URL specifies the incident id of the row we would like to edit - save this id number
}
?>

<html>
<head>
<title>Edit Incident</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 
</head>

<header>
    <p><a href="searchreport.php?flag=3"><i>Back</i></a></p>
</header>

    <section>
    <table> <!-- create a table containing the single row we would like to edit -->
    <thead>
    <tr><th>Incident ID</th><th>Vehicle ID</th><th>People ID</th><th>Incident Date</th><th>Report</th><th>Offence_ID</th></tr>
    </thead>
    <?php
    $sql = "SELECT * FROM Incident WHERE Incident_ID=".$_SESSION['id'].";"; // select the row from the Incident table corresponding to the ID of the incident we wish to edit
    $result=mysqli_query($conn,$sql);
    $array=mysqli_fetch_array($result);
    echo "<tr><td>$array[0]</td><td>$array[1]</td><td>$array[2]</td><td>$array[3]</td><td>$array[4]</td><td>$array[5]</td></tr>"; // insert the SQL output into the table
    echo 
    '</table>
    </section><br/>';

    echo // Creates a form that allows the user to enter any changes they would like to make to the row
    '<section>
    <form action="editreport.php" method="post">
    <header>
    <h2>You can make changes to this incident here:</h2>
    </header>
        <label for="newdate">Incident Date:</label>
        <input type="text" name="newdate"><br/>
        <label for="newreport">Incident Report:</label>
        <input type="text" name="newreport"><br/>
        <label for="newvehicle">Vehicle Involved (Licence Plate):</label>
        <input type="text" name="newvehicle"><br/>
        <label for="newperson">Person Involved (Licence Number):</label>
        <input type="text" name="newperson"><br/>
        <label for="newoffence">Offence Involved (ID):</label>
        <input type="text" name="newoffence"><br/>
    <button type="submit">Submit</button>
    </form>
    </section>'; // THE PHP FORM SHOULD NOT GO INSIDE THE TABLE, TRY TO PUT THIS FORM AFTER THE TABLE HAS BEEN PRINTED CONDITIONAL ON THERE BEING ONE ROW
    


	footer();
	?>
</html>