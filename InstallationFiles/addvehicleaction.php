<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

if (!$_GET) // If we are visiting this page for the first time, this means that the main vehicle-adding form has just been submitted, so record the POST data as SESSION variables for later use
{
    $_SESSION["make"]=$_POST["make"];
    $_SESSION["colour"]=$_POST["colour"];

    $_SESSION["model"]=""; // Reset optional variables in case user wishes to fill out the vehicle-adding form multiple times
    $_SESSION["plate"]="";

    if ($_POST["model"]!="") // For the optional variables, only record session variables if they are filled in
    {
        $_SESSION["model"]=$_POST["model"];
    }

    if ($_POST["plate"]!="")
    {
        $_SESSION["plate"]=$_POST["plate"];
    }
    
    if ($_POST["choice"]=="No") // Skip most of the code if the officer doesn't wish to add a corresponding owner to the new vehicle
    {
        header("Location: addvehicleaction.php?flag=1");
    }

    else // But if the officer does wish to add a corresponding owner, return to the 'addvehicle' page and display the supplementary form
    {
        header("Location: addvehicle.php?flag=1");
    }
}

else // This part of the code will run when we are redirected to the addvehicleaction page for the second time
{
    // This conditionals will insert the new vehicle into the database, using different queries depending on which optional fields are filled in
    if ($_SESSION["model"]!="" && $_SESSION["plate"]!="") // There are four conditionals depending on which optional fields are filled in
    {
        $cartype=$_SESSION["make"] . " " . $_SESSION["model"]; // Since Vehicle_type is a concatenation of 'make' and 'model' we need to add these two strings before inserting into the Vehicle table
        $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour, Vehicle_licence) VALUES ('$cartype', '".$_SESSION["colour"]."', '".$_SESSION["plate"]."');";
        $result=mysqli_query($conn,$sql);
    }
    elseif ($_SESSION["model"]!="")
    {
        $cartype=$_SESSION["make"] . " " . $_SESSION["model"];
        $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour) VALUES ('$cartype', '".$_SESSION["colour"]."');"; // These queries will insert the new vehicle into the Vehicle table
        $result=mysqli_query($conn,$sql);
    }
    elseif ($_SESSION["plate"]!="")
    {
        $cartype=$_SESSION["make"];
        $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour, Vehicle_licence) VALUES ('$cartype', '".$_SESSION["colour"]."', '".$_SESSION["plate"]."');";
        $result=mysqli_query($conn,$sql);
    }
    else
    {
        $cartype=$_SESSION["make"];
        $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour) VALUES ('$cartype', '".$_SESSION["colour"]."');";
        $result=mysqli_query($conn,$sql);
    }

    if ($_GET['flag']==1) // Go straight back to the addvehicle page and display a confirmation message if the officer doesn't wish to add a corresponding owner
    {
        header("Location: addvehicle.php?flag=3");
    }
    elseif ($_GET['flag']==2) // This executes if a corresponding owner needs to be added and makes use of the information filled out in the supplementary form
    {
        if ($_POST["exowner"]!="" || $_POST["personid"]) // Evaluates True when an existing owner has been selected
        {
            if ($_POST["exowner"]!="") // True when owner is specified using licence number
            {
                $sql = "SELECT People_ID FROM People WHERE People_licence='".$_POST["exowner"]."';"; // Finds the ID corresponding to the licence number selected
                $result=mysqli_query($conn,$sql);
                $ownerid=mysqli_fetch_array($result)[0]; // Stores the person id

            }
            else // Runs when owner is specified using ID number
            {
                $ownerid=$_POST["personid"]; // The ID is provided directly so just store this
            }

        }
        else // Runs when a new owner has been selected
        {
            $name=$_POST["name"];

            // Depending on which optional fields have been filled in, an SQL query will be executed to insert the new person that has been specified by the officer - this needs to be done before finding their new person id
            if ($_POST["address"]!="" && $_POST["newowner"]!="")
            {
                // Insert new person into the People table
                $address=$_POST["address"];
                $newowner=$_POST["newowner"];
                $sql = "INSERT INTO People (People_name, People_address, People_licence) VALUES ('$name','$address','$newowner');"; // Various SQL queries to add the new person (of differing lengths depending on the amount of information provided)
                $result=mysqli_query($conn,$sql);
            }
        
            elseif ($_POST["address"]!="")
            {
                $address=$_POST["address"];
                $sql = "INSERT INTO People (People_name, People_address) VALUES ('$name','$address');";
                $result=mysqli_query($conn,$sql);
            }
            
            elseif ($_POST["newowner"]!="")
            {
                $newowner=$_POST["newowner"];
                $sql = "INSERT INTO People (People_name, People_licence) VALUES ('$name','$newowner');";
                $result=mysqli_query($conn,$sql);
            }
            // After we have added the new owner, we record their person ID
            $sql = "SELECT People_ID FROM People ORDER BY People_ID DESC LIMIT 1;"; // To get the new person ID, we sort the IDs in decending order and take the first entry - this is because the person we just added to the table will have the last person id
            $result=mysqli_query($conn,$sql);
            $ownerid=mysqli_fetch_array($result)[0];

        }
        // Now that we have got the Person ID, we record the vehicle ID so that we can add the ownership relation using both IDs
        $sql = "SELECT Vehicle_ID FROM Vehicle ORDER BY Vehicle_ID DESC LIMIT 1;"; // New vehicle entry we just added will have the last id number
        $result=mysqli_query($conn,$sql);
        $vehicleid=mysqli_fetch_array($result)[0];

        $sql = "INSERT INTO Ownership (People_ID, Vehicle_ID) VALUES ($ownerid, $vehicleid);"; // We can finally add the new ownership relation between the new vehicle and the specified person
        $result=mysqli_query($conn,$sql);

        header("Location: addvehicle.php?flag=2"); // Now that the vehicle and ownership relation (and possibly new person) have been added, we return to the addvehicle page and display the appropriate confirmation message
    }
}
?>