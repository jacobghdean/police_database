<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);
?>

<html>
<head>
<title>Add Vehicle</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css">

<script>

    function validateForm0() // This function provides validation for the main form (where vehicle details are entered), it asks the officer for confirmation that they would like to enter the new vehicle
        {
            var x = document.forms["initial"]["choice"].value; // Records whether the officer would also like to add the person who owns the vehicle
            if (x=="No") // Only show the message asking for confirmation if the officer doesn't wish to also add the owner, this is because if he clicks 'Yes' the officer will be presented with another form to add the owner before the vehicle is also added
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


    function validateForm()
    // This function provides validation for the supplementary form (where the officer adds details for the owner of the vehicle they are adding)
    // It includes a portfolio of checks to ensure that only appropriate user input is posted to the addvehicleaction.php page
    {
        var ex1 = document.forms["Vehicle"]["exowner"].value;
        var ex2 = document.forms["Vehicle"]["personid"].value;

        var new1 = document.forms["Vehicle"]["name"].value;
        var new2 = document.forms["Vehicle"]["address"].value;
        var new3 = document.forms["Vehicle"]["newowner"].value;

        if ((ex1 != "" || ex2 != "") && (new1 != "" || new2 != "" || new3 != "")) // True if the officer has included at least one input for both a new and an existing owner
        {
            alert("You cannot assign both an existing and a new person!");
            return false;
        }
        else if (ex1 != "" && ex2 != "") // True if the officer has tried using both a licence number and and ID number to identify an existing person
        {
            alert("Please choose only one method of identification!");
            return false;
        }
        else if (new1 == "" && (new2 != "" || new3 != "")) // True if the officer is trying to add a new person but has not filled in the required 'Name' field
        {
            alert("Owner Name is a required field!");
            return false;
        }
        else if (ex1 == "" && ex2 == "" && new1 == "" && new2 == "" && new3 == "") // True if the officer tries to submit the form without including anything
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
</script>
</head>

<header>
<?php
if (!$_GET || $_GET['flag']==3) // If the officer is on the main form, only show the 'search vehicle' button, but if they are on the supplementary form, also provide a button to allow them to go back tot the main form
{
    echo '<p><a href="searchvehicle.php"><i>Search Vehicle</i></a></p>';
}
else
{
    echo '<p><a href="searchvehicle.php"><i>Search Vehicle</i></a><a href="addvehicle.php"><b>Back</b></a></p>';
}
?>
</header>

<?php
if (!$_GET || $_GET['flag']==3) // If the officer has visited the page for the first time or has just finished adding a new vehicle without an owner, display the main form
{
    // The main form for adding a new vehicle (includes radio buttons where the officer can decide whether to add a corresponding owner)
    echo '
    <section>
    <form name="initial" onsubmit="return validateForm0()" action="addvehicleaction.php" method="post">

    <header>
    <h2>Add Vehicle</h2>
    </header>

    <label for="make">Make:*</label>
    <input type="text" name="make" required><br/>
    <label for="model">Model:</label>
    <input type="text" name="model"><br/>
    <label for="colour">Colour:*</label>
    <input type="text" name="colour" required><br/>
    <label for="plate">Licence Plate:</label>
    <input type="text" name="plate"><br/>

    <label for="choice">Add Corresponding Owner?*</label>

    <input type="radio" name="choice" value="Yes" required> Yes
    <input type="radio" name="choice" value="No" required> No

    <br/><br/>
    <i>* Required Field</i><br/><br/>
    <button type="submit">Submit</button>
  
    </form>
    </section>
    ';
}

if ($_GET)
    {
        if ($_GET['flag']==1 || $_GET['flag']==2) // If the officer has indicated that he would like to add a corresponding owner of the new vehicle, show this supplementary form
        {
            // The supplementary form for adding a corresponding owner to the new vehicle
            // This includes drop down menus where the officer can decide whether to use an existing person or to add a new person
            // There is also inline SQL which generates a drop down list of driving licences to make it easier to select an existing person
            // Since it is not guaranteed that driving licence is recorded for all people, there is an alternative way of specifying an existing person using their person ID
            echo '
            <section>
            <form name="Vehicle" onsubmit="return validateForm()" action="addvehicleaction.php?flag=2" method="post">
            <header>
            <h2>Add Owner</h2>
            </header>
            <details>
            <summary>Choose Existing Person</summary><br/>
            <label for="exowner">Owner Licence:</label>
            <select name="exowner">
            <option value="">--Choose licence--</option>
            ';
           
            $sql="SELECT People_licence FROM People WHERE People_licence != '' ORDER BY People_licence;"; // When generating a list of driving licences, remove any people without a driving licence (they can still be selected using their person ID in the next field)
            $result=mysqli_query($conn,$sql);
            while ($row=mysqli_fetch_array($result)) // Pipe the list of driving licences as options in the drop down menu
                {
                    echo "<option value='$row[0]'>$row[0]</option>";           
                }
                
            echo '
            </select>
            <input type="text" name="personid" placeholder="Or type database ID here">
            </details>
      
            <details>
            <summary>Add New Person</summary><br/>
            <label for="name">Owner Name:*</label>
            <input type="text" name="name"><br/>
            <label for="address">Owner Address:</label>
            <input type="text" name="address"><br/>
            <label for="newowner">Owner Licence:</label>
            <input type="text" name="newowner">
            <i>* Required Field</i><br/>
            </details>
      
            <button type="submit">Submit</button>
            </form>
            </section>
            ';
        }
        if ($_GET['flag']==3) // Choose one of two confirmation messages depending on whether the officer decided to assign a corresponding owner to the vehicle they added
        {
            echo "<p>Vehicle added</p>";
        }
        if ($_GET['flag']==2)
        {
            echo "<p>Owner added</p><br/>";
            echo "<p>Vehicle added</p>";
        }
    }

	footer();
	?>
</html>
