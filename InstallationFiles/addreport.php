<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);
?>

<html>
<head>
<title>Add Incident</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 

<script>
    function validateForm0() // Validates the main form where the incident details are recorded
    {
        var x = document.forms["initial"]["choice1"].value;
        var y = document.forms["initial"]["choice2"].value;

        if (x=="No" && y=="No") // if the officer doesn't wish to move onto any supplementary forms, then they must be asked to confirm that they wish to add the incident without vehicle or person information
        {
            text = "Would you like to make these changes?\nSelect OK or Cancel."; // see https://www.w3schools.com/jsref/met_win_confirm.asp
            if (confirm(text) == true)
            {
                pass
            } 
            else 
            {
                return false
            }
        }

    }

    function validateForm1() // Validates the first supplementary form where both the vehicle and person responsible for the incident are recorded
    {
        var exa1 = document.forms["addboth"]["exowner"].value;
        var exa2 = document.forms["addboth"]["personid"].value;

        var newa1 = document.forms["addboth"]["name"].value;
        var newa2 = document.forms["addboth"]["address"].value;
        var newa3 = document.forms["addboth"]["owner"].value;

        var exb1 = document.forms["addboth"]["explate"].value;
        var exb2 = document.forms["addboth"]["vehicleid"].value;

        var newb1 = document.forms["addboth"]["newincmake"].value;
        var newb2 = document.forms["addboth"]["newincmodel"].value;
        var newb3 = document.forms["addboth"]["newinccolour"].value;
        var newb4 = document.forms["addboth"]["newplate"].value;

        // These are a set of conditions that provide various types of validation (many of these are similar to those found in addreport.php)

        if ((exa1 != "" || exa2 != "") && (newa1 != "" || newa2 != "" || newa3 != ""))
        {
            alert("You cannot assign both an existing and a new person!");
            return false;
        }
        else if ((exb1 != "" || exb2 != "") && (newb1 != "" || newb2 != "" || newb3 != "" || newb4 != ""))
        {
            alert("You cannot assign both an existing and a new vehicle!");
            return false;
        }
        else if ((exa1 != "" && exa2 != "") || (exb1 != "" && exb2 != ""))
        {
            alert("Please choose only one method of identification!");
            return false;
        }

        // This is a new condition that tests if the user does not enter something in both the fields for adding a new person and a new vehicle
        else if (!((exa1 != "" || exa2 != "" || newa1 != "" || newa2 != "" || newa3 != "") && (exb1 != "" || exb2 != "" || newb1 != "" || newb2 != "" || newb3 != "" || newb4 != "")))
        {
            alert("You must enter both a person and a vehicle!");
            return false;
        }
        else if (newa1 == "" && (newa2 != "" || newa3 != ""))
        {
            alert("Owner Name is a required field!");
            return false;
        }

        // These are the conditions that test if required fields for vehicles are not filled in (but the officer seemingly wishes to add a new vehicle)
        else if (newb1 == "" && (newb2 != "" || newb4 != ""))
        {
            alert("Vehicle Make is a required field!");
            return false;
        }
        else if (newb3 == "" && (newb2 != "" || newb4 != ""))
        {
            alert("Vehicle Colour is a required field!");
            return false;
        }

        text = "Would you like to make these changes?\nSelect OK or Cancel."; // requires confirmation before adding a person and vehicle to the incident record
        
        if (confirm(text) == true)
        {
            pass
        } 
        else 
        {
            return false
        }
    }


    function validateForm2() // Very similar to the above function, however this validates the supplementary form where the officer only wishes to add a person to the incident
    {
        var ex1 = document.forms["addperson"]["exowner"].value;
        var ex2 = document.forms["addperson"]["personid"].value;

        var new1 = document.forms["addperson"]["name"].value;
        var new2 = document.forms["addperson"]["address"].value;
        var new3 = document.forms["addperson"]["owner"].value;

        if ((ex1 != "" || ex2 != "") && (new1 != "" || new2 != "" || new3 != ""))
        {
            alert("You cannot assign both an existing and a new person!");
            return false;
        }
        else if (ex1 != "" && ex2 != "")
        {
            alert("Please choose only one method of identification!");
            return false;
        }
        else if (new1 == "" && (new2 != "" || new3 != ""))
        {
            alert("Owner Name is a required field!");
            return false;
        }
        else if (ex1 == "" && ex2 == "" && new1 == "" && new2 == "" && new3 == "")
        {
            alert("You must assign a person!");
            return false;
        }

        text = "Would you like to make these changes?\nSelect OK or Cancel."; // see https://www.w3schools.com/jsref/met_win_confirm.asp
        
        if (confirm(text) == true)
        {
            pass
        } 
        else 
        {
            return false
        }
    }

    function validateForm3() // Very similar to the above functions, however this validates the supplementary form where the officer only wishes to add a vehicle to the incident
    {
        var ex1 = document.forms["addvehicle"]["explate"].value;
        var ex2 = document.forms["addvehicle"]["vehicleid"].value;

        var new1 = document.forms["addvehicle"]["newincmake"].value;
        var new2 = document.forms["addvehicle"]["newincmodel"].value;
        var new3 = document.forms["addvehicle"]["newinccolour"].value;
        var new4 = document.forms["addvehicle"]["newplate"].value;

        if ((ex1 != "" || ex2 != "") && (new1 != "" || new2 != "" || new3 != "" || new4 != ""))
        {
            alert("You cannot assign both an existing and a new vehicle!");
            return false;
        }
        else if (ex1 != "" && ex2 != "")
        {
            alert("Please choose only one method of identification!");
            return false;
        }
        else if (new1 == "" && (new2 != "" || new3 != "" || new4 != ""))
        {
            alert("Vehicle Make is a required field!");
            return false;
        }
        else if (new3 == "" && (new2 != "" || new1 != "" || new4 != ""))
        {
            alert("Vehicle Colour is a required field!");
            return false;
        }
        else if (ex1 == "" && ex2 == "" && new1 == "" && new2 == "" && new3 == "" && new4 == "")
        {
            alert("You must assign a vehicle!");
            return false;
        }

        text = "Would you like to make these changes?\nSelect OK or Cancel."; // see https://www.w3schools.com/jsref/met_win_confirm.asp
        
        if (confirm(text) == true)
        {
            pass
        } 
        else 
        {
            return false
        }
    }
</script>
</head>

<header>
    <?php
    if (!$_GET || $_GET["flag"]==7) // Display an extra back button if the officer is filling in a supplementary form
    {
        echo '<p><a href="searchreport.php"><i>Search Incident</i></a></p>';
    }
    else
    {
        echo '<p><a href="searchreport.php"><i>Search Incident</i></a><a href="addreport.php"><b>Back</b></a></p>';
    }
    ?>
</header>

<br/>
<?php
if (!$_GET || $_GET["flag"]==7) // The main form is displayed if the officer has visited the page for the first time or if he is returning to the page after adding a new incident without a vehicle and person
{
    echo // Here is the main form, it contains two sets of radio buttons to give the option to add a vehicle and the option to add a person to the incident
    '<section>
    <form name="initial" onsubmit="return validateForm0()" action="addreportaction.php" method="post">

    <header>
    <h2>Add New Incident Report</h2>
    </header>


        <label for="date">Incident Date:*</label>
        <input type="text" name="date" required><br/>
        <label for="report">Incident Report:*</label>
        <input type="text" name="report" required><br/>
        <label for="offence_involved">Offence Involved (ID):</label>
        <input type="text" name="offence_involved">


        <label for="choice1">Add Offending Individual?*</label>
        <input type="radio" name="choice1" value="Yes" required> Yes
        <input type="radio" name="choice1" value="No" required> No <br/>
        <label for="choice2">Add Vehicle Involved?*</label>
        <input type="radio" name="choice2" value="Yes" required> Yes
        <input type="radio" name="choice2" value="No" required> No
        
        </label><br/><br/>
        <i>* Required Field</i><br/><br/>
        <button type="submit">Submit</button>

    </form>
    </section>';

    if ($_GET) // Validation message if only an incident has been added
    {
        echo "<p>Incident Added</p>";
    }
}

if ($_GET) // This outer conditional is needed to prevent the following code from running if a flag has not been set (will lead to an empty array error)
{
    if ($_GET["flag"]==1 || $_GET["flag"]==4) // Runs when the officer has chosen to add both a vehicle and a person to the incident (or the entries have just been added to the database)
    {
        echo // This form contains two sets of drop down menus where the officer can choose whether to add an existing or a new person and vehicle to the incident
        // Also the in-line SQL queries generate drop down lists of driving licences for people and number plates for vehicles (much like in the addvehicle.php file)
        ' <section>
        <form name="addboth" onsubmit="return validateForm1()" action="addreportaction.php?flag=2" method="post">

        <header>
        <h2>Add Individual & <br/>Vehicle Involved</h2>
        </header>

        <p><i>Enter Offending Individual</i></p>

        <details>
        <summary>Existing Person</summary><br/>
        <label for="exowner">Owner Licence:</label>
        <select name="exowner">
        <option value="">--Choose licence--</option>
        ';

        $sql="SELECT People_licence FROM People WHERE People_licence != '' ORDER BY People_licence;";
        $result=mysqli_query($conn,$sql);
        while ($row=mysqli_fetch_array($result))
            {
                echo "<option value='$row[0]'>$row[0]</option>";
            }
        
        echo '
        </select>
        <input type="text" name="personid" placeholder="Or type database ID here">
        </details>

        <details>
        <summary>New Person</summary><br/>
        <label for="name">Owner Name:*</label>
        <input type="text" name="name"><br/>
        <label for="address">Owner Address:</label>
        <input type="text" name="address"><br/>
        <label for="owner">Owner Licence:</label>
        <input type="text" name="owner">
        </details>

        <p><i>Enter Vehicle Involved</i></p>

        <details>
        <summary>Existing Vehicle</summary><br/>
        <label for="explate">Licence Plate:</label>
        <select name="explate">
        <option value="">--Choose licence--</option>
        ';

        $sql="SELECT Vehicle_licence FROM Vehicle WHERE Vehicle_licence != '' ORDER BY Vehicle_licence;";
        $result=mysqli_query($conn,$sql);
        while ($row=mysqli_fetch_array($result))
            {
                echo "<option value='$row[0]'>$row[0]</option>";
            }
        
        echo '
        </select>
        <input type="text" name="vehicleid" placeholder="Or type database ID here">
        </details>

        <details>
        <summary>New Vehicle</summary><br/>
        <label for="newincmake">Vehicle Make*:</label>     
        <input type="text" name="newincmake"><br/>
        <label for="newincmodel">Vehicle Model:</label>
        <input type="text" name="newincmodel"><br/>
        <label for="newinccolour">Vehicle Colour*:</label>
        <input type="text" name="newinccolour"><br/>
        <label for="newplate">Licence Plate:</label>
        <input type="text" name="newplate">
        <i>* Required Field</i><br/>
        </details>

        <button type="submit">Submit</button>
            
        </form>
        </section>
        ';


        if ($_GET["flag"]==4) // After the incident and corresponding person and vehicle have been added, we show this confirmation message
        {
            echo "<p>Person Added<br/>
            Vehicle Added<br/>
            Incident Added</p>";
        }
    }

    elseif ($_GET["flag"]==2 || $_GET["flag"]==5) # If the officer has chosen to only add a corresponding person, this form will be displayed
    {

        echo '
        <section>
        <form name="addperson" onsubmit="return validateForm2()" action="addreportaction.php?flag=3" method="post">

        <header>
        <h2>Add Individual Involved</h2>
        </header>

        <details>
        <summary>Existing Person</summary><br/>
        <label for="exowner">Owner Licence:</label>
        <select name="exowner">
        <option value="">--Choose licence--</option>
        ';

        $sql="SELECT People_licence FROM People WHERE People_licence != '' ORDER BY People_licence;";
        $result=mysqli_query($conn,$sql);
        while ($row=mysqli_fetch_array($result))
            {
                echo "<option value='$row[0]'>$row[0]</option>";
            }
            
        echo '
        </select>
        <input type="text" name="personid" placeholder="Or type database ID here">
        </details>

        <details>
        <summary>New Person</summary><br/>
        <label for="name">Owner Name:*</label>
        <input type="text" name="name"><br/>
        <label for="address">Owner Address:</label>
        <input type="text" name="address"><br/>
        <label for="owner">Owner Licence:</label>
        <input type="text" name="owner">
        <i>* Required Field</i><br/>
        </details>
        
        <button type="submit">Submit</button>
            
        </form>
        </section>
        ';

        if ($_GET["flag"]==5)
        {
            echo "<p>Person Added<br/>Incident Added</p>";
        }
    }
    
    elseif ($_GET["flag"]==3 || $_GET["flag"]==6) # If the officer has chosen to only add a vehicle to the incident, this form will be displayed
    {
        echo '
        <section>
        <form name="addvehicle" onsubmit="return validateForm3()" action="addreportaction.php?flag=4" method="post">

        <header>
        <h2>Add Vehicle Involved</h2>
        </header>

        <details>
        <summary>Existing Vehicle</summary><br/>
        <label for="explate">Licence Plate:</label>
        <select name="explate">
        <option value="">--Choose licence--</option>
        ';

        $sql="SELECT Vehicle_licence FROM Vehicle WHERE Vehicle_licence != '' ORDER BY Vehicle_licence;";
        $result=mysqli_query($conn,$sql);
        while ($row=mysqli_fetch_array($result))
            {
                echo "<option value='$row[0]'>$row[0]</option>";
            }

        echo '
        </select>
        <input type="text" name="vehicleid" placeholder="Or type database ID here">
        </details>

        <details>
        <summary>New Vehicle</summary><br/>
        <label for="newincmake">Vehicle Make*:</label>     
        <input type="text" name="newincmake"><br/>
        <label for="newincmodel">Vehicle Model:</label>
        <input type="text" name="newincmodel"><br/>
        <label for="newinccolour">Vehicle Colour*:</label>
        <input type="text" name="newinccolour"><br/>
        <label for="newplate">Licence Plate:</label>
        <input type="text" name="newplate">
        <i>* Required Field</i><br/>
        </details>

        <button type="submit">Submit</button>
            
        </form>
        </section>
        ';

        if ($_GET["flag"]==6)
        {
            echo "<p>Vehicle Added<br/>Incident Added</p>";
        }
    }
}

footer();
?>
</html>