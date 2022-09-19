<?php
require __DIR__ . '/functions.php';
verify();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);

if (!$_GET) // If we are visiting this page for the first time, this means that the main incident-adding form has just been submitted, so record the POST data as SESSION variables for later use
{
    $_SESSION["offence_involved"]=""; // Reset optional variable in case user wishes to fill out the vehicle-adding form multiple times

    $_SESSION["date"]=$_POST["date"];
    $_SESSION["report"]=$_POST["report"];

    if ($_POST["offence_involved"]!="") // For the optional variables, only record session variables if they are filled in
    {
        $_SESSION["offence_involved"]=$_POST["offence_involved"];
    }
    
    // The following conditionals decide what supplementary form (if any) should be displayed to the officer depending on whether they want to add a vehicle and person to the incident
    if ($_POST["choice1"]=="Yes" && $_POST["choice2"]=="Yes")
    {
        header("Location: addreport.php?flag=1");
    }
    elseif ($_POST["choice1"]=="Yes")
    {
        header("Location: addreport.php?flag=2");
    }
    elseif ($_POST["choice2"]=="Yes")
    {
        header("Location: addreport.php?flag=3");
    }
    else
    {
        header("Location: addreportaction.php?flag=1"); // Skip most of the code if the officer doesn't wish to add a corresponding owner and vehicle to the new incident
    }
}
else // This part of the code will run when we are redirected to the addreportaction page for the second time
{
    if ($_GET['flag']==2 || $_GET['flag']==3) // Evaluates true when the officer has decided to attach a person to the incident
    {
        if ($_POST["exowner"]!="" || $_POST["personid"]!="") // True when the officer wishes to use an existing person
        {
            if ($_POST["exowner"]!="") // if licence number has been used to identify the person responsible
            {
                $sql = "SELECT People_ID FROM People WHERE People_licence='".$_POST["exowner"]."';"; // record the ID of the person responsible
                $result=mysqli_query($conn,$sql);
                $offenderid=mysqli_fetch_array($result)[0];

            }
            else // specified using id number
            {
                $offenderid=$_POST["personid"];
            }
        }
        else // Evaluates when the officer wishes to add a new person
        {
            $name=$_POST["name"];

            // A set of conditionals that will run various SQL queries to add the new person into the database depending on the amount of optional fields that are filled in
            if ($_POST["address"]!="" && $_POST["owner"]!="")
            {
                $address=$_POST["address"];
                $owner=$_POST["owner"];
                $sql = "INSERT INTO People (People_name, People_address, People_licence) VALUES ('$name','$address','$owner');"; // Inserts new person into the People table
                $result=mysqli_query($conn,$sql);
            }
        
            elseif ($_POST["address"]!="")
            {
                $address=$_POST["address"];
                $sql = "INSERT INTO People (People_name, People_address) VALUES ('$name','$address');";
                $result=mysqli_query($conn,$sql);
            }
            
            elseif ($_POST["owner"]!="")
            {
                $owner=$_POST["owner"];
                $sql = "INSERT INTO People (People_name, People_licence) VALUES ('$name','$owner');";
                $result=mysqli_query($conn,$sql);
            }
            else
            {
                $sql = "INSERT INTO People (People_name) VALUES ('$name');"; // The shortest query is when only 'Person Name' is specified
                $result=mysqli_query($conn,$sql);
            }
            // Get the new Person ID after adding in the new person
            $sql = "SELECT People_ID FROM People ORDER BY People_ID DESC LIMIT 1;"; // We just added the new person so their ID will be last
            $result=mysqli_query($conn,$sql);
            $offenderid=mysqli_fetch_array($result)[0];
        }
    }
    if ($_GET['flag']==2 || $_GET['flag']==4) // This conditional evaluates true when the officer has chosen to add the vehicle invovled in the incident 
    {
        if ($_POST["explate"]!="" || $_POST["vehicleid"]) // True when the officer decides to assign an existing vehicle
        {
            // We collect the Vehicle ID of the existing vehicle using either number plate or ID directly
            if ($_POST["explate"]!="")
            {
                $sql = "SELECT Vehicle_ID FROM Vehicle WHERE Vehicle_licence='".$_POST["explate"]."';";
                $result=mysqli_query($conn,$sql);
                $vehicleid=mysqli_fetch_array($result)[0];
            }
            else
            {
                $vehicleid=$_POST["vehicleid"];
            }
        }
        else // Evaluates when the officer decides to add a new vehicle
        {
            $make=$_POST["newincmake"];
            $colour=$_POST["newinccolour"];

            // Various queries to account for the differing numbers of optional vehicle fields that the officer filled in when creating the new vehicle
            if ($_POST["newincmodel"]!="" && $_POST["newplate"]!="")
            {
                $cartype=$make . " " . $_POST["newincmodel"];
                $plate=$_POST["newplate"];
                $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour, Vehicle_licence) VALUES ('$cartype','$colour','$plate');"; // Insert new vehicle into the Vehicle table
                $result=mysqli_query($conn,$sql);
            }
        
            elseif ($_POST["newincmodel"]!="")
            {
                $cartype=$make . " " . $_POST["newincmodel"];
                $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour) VALUES ('$cartype','$colour');";
                $result=mysqli_query($conn,$sql);
            }
            
            elseif ($_POST["newplate"]!="")
            {
                $cartype=$make;
                $plate=$_POST["newplate"];
                $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour, Vehicle_licence) VALUES ('$cartype','$colour','$plate');";
                $result=mysqli_query($conn,$sql);
            }
            else
            {
                $cartype=$make;
                $plate=$_POST["newplate"];
                $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour) VALUES ('$cartype','$colour');";
                $result=mysqli_query($conn,$sql);
            }

            // After creating the new vehicle entry, record its ID
            $sql = "SELECT Vehicle_ID FROM Vehicle ORDER BY Vehicle_ID DESC LIMIT 1;"; // Vehicle just added so its ID will be last
            $result=mysqli_query($conn,$sql);
            $vehicleid=mysqli_fetch_array($result)[0];
        }
    }

    // Now that we have all the necessary information, we can add the incident
    if ($_GET['flag']==2) // This runs when we wish to add the incident with both vehicle and person id
    {
        echo $vehicleid;
        echo $offenderid;
        // add new incident
        if ($_SESSION["offence_involved"]!="") // Offence ID is an optional variable so we need two SQL queries depending on whether the officer entered information for it
        {
            // This SQL query will insert the new incident using all the information we have collected
            $sql = "INSERT INTO Incident (Vehicle_ID,People_ID,Incident_Date,Incident_Report,Offence_ID)
            VALUES ($vehicleid,$offenderid,'".$_SESSION["date"]."', '".$_SESSION["report"]."','".$_SESSION["offence_involved"]."');";
            $result=mysqli_query($conn,$sql);            
        }
        else
        {
            $sql = "INSERT INTO Incident (Vehicle_ID,People_ID,Incident_Date,Incident_Report)
            VALUES ($vehicleid,$offenderid,'".$_SESSION["date"]."', '".$_SESSION["report"]."');";
            $result=mysqli_query($conn,$sql); // no quotation marks around things like $array2[0] because these are meant to be integers            
        }
    }
    elseif ($_GET['flag']==3) // This runs when we wish to add the incident with only person id
    {
        // add new incident
        if ($_SESSION["offence_involved"]!="") // Offence ID is an optional variable so we need two SQL queries depending on whether the officer entered information for it
        {
            $sql = "INSERT INTO Incident (People_ID,Incident_Date,Incident_Report,Offence_ID)
            VALUES ($offenderid,'".$_SESSION["date"]."', '".$_SESSION["report"]."','".$_SESSION["offence_involved"]."');";
            $result=mysqli_query($conn,$sql); // no quotation marks around things like $array2[0] because these are meant to be integers            

        }
        else
        {
            $sql = "INSERT INTO Incident (People_ID,Incident_Date,Incident_Report)
            VALUES ($offenderid,'".$_SESSION["date"]."', '".$_SESSION["report"]."');";
            $result=mysqli_query($conn,$sql); // no quotation marks around things like $array2[0] because these are meant to be integers                
        }
    }
    elseif ($_GET['flag']==4) // This runs when we wish to add the incident with only vehicle id
    {
        // add new incident
     
        if ($_SESSION["offence_involved"]!="") // Offence ID is an optional variable so we need two SQL queries depending on whether the officer entered information for it
        {
            $sql = "INSERT INTO Incident (Vehicle_ID,Incident_Date,Incident_Report,Offence_ID)
            VALUES ($vehicleid,'".$_SESSION["date"]."', '".$_SESSION["report"]."','".$_SESSION["offence_involved"]."');";
            $result=mysqli_query($conn,$sql); // no quotation marks around things like $array2[0] because these are meant to be integers            
        }
        else
        {
       
            $sql = "INSERT INTO Incident (Vehicle_ID,Incident_Date,Incident_Report)
            VALUES ($vehicleid,'".$_SESSION["date"]."', '".$_SESSION["report"]."');";
            $result=mysqli_query($conn,$sql); // no quotation marks around things like $array2[0] because these are meant to be integers            
        }
    }
    elseif ($_GET['flag']==1) // This runs when we wish to add the incident with neither vehicle id nor person id
    {
        if ($_SESSION["offence_involved"]!="") // Offence ID is an optional variable so we need two SQL queries depending on whether the officer entered information for it
        {
            $sql = "INSERT INTO Incident (Incident_Date,Incident_Report,Offence_ID)
            VALUES ('".$_SESSION["date"]."', '".$_SESSION["report"]."','".$_SESSION["offence_involved"]."');";
            $result=mysqli_query($conn,$sql); // no quotation marks around things like $array2[0] because these are meant to be integers            
        }
        
        else
        {
            $sql = "INSERT INTO Incident (Incident_Date,Incident_Report)
            VALUES ('".$_SESSION["date"]."', '".$_SESSION["report"]."');";
            $result=mysqli_query($conn,$sql); // no quotation marks around things like $array2[0] because these are meant to be integers            
        }
    }

    // Now that the incident has been added, we redirect back to the addreport page with different flags to show different validation messages depending on whether a vehicle and person were added to the incident
    if ($_GET['flag']==1)
    {
        header("Location: addreport.php?flag=7");
    }
    elseif ($_GET['flag']==2)
    {
        header("Location: addreport.php?flag=4");
    }
    elseif ($_GET['flag']==3)
    {
        header("Location: addreport.php?flag=5");
    }
    elseif ($_GET['flag']==4)
    {
        header("Location: addreport.php?flag=6");
    }

}
?>