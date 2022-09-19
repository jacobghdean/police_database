<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);
?>

<html>

<head>
<title>Search Vehicle</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 
</head>

<header> <!-- Add three hyperlink buttons that take you to the 'add vehicle' page, view all people, or clear search results respectively -->
<p><a href="addvehicle.php"><i>Add Vehicle</i></a><a href="searchvehicle.php?flag=4"><i>View All</i></a></p><a href="searchvehicle.php?"><b>Clear Results</b></a><p>
</header>

    <section> <!-- Add a form that allows the user to search for a vehicle by licence plate-->
    <form  action="searchvehicle.php?flag=1" method="post">
    <header>
        <h2>Search Vehicle</h2>
    </header>
        <label for="plate">Enter vehicle licence plate:</label>
        <input type="text" name="plate"><br/>
        <button type="submit">Submit</button>
    </form>
    </section>

    <?php
    if ($_GET)
    {
        if($_GET["flag"]==1 || $_GET["flag"]==3 || $_GET["flag"]==4) // If the flags take these values, we proceed with the search
        {
            echo "
            <section>
            <table>
            <thead>
            <tr><th>Type</th><th>Colour</th><th>Licence Plate</th><th>Owner Name</th><th>Owner Licence</th><th></th><th></th></tr> <!-- https://www.quora.com/How-do-I-make-a-table-in-PHP-using-HTML -->
            </thead>
            ";

            if($_GET["flag"]==1) // If the user filled in the form, store their input
            {
                $_SESSION["vehicle"]=$_POST["plate"];
            }
            if ($_GET["flag"]==4)
            {
                $_SESSION["vehicle"]=""; // Clear the search input if the user clicks the 'view all' button: prevents bug where you try to search, then view all then delete but it stores and shows the searched rather than view all version
            }
            if($_GET["flag"]!=4)
            {
                /*This query first executes a subquery that uses an inner join to connect vehicle ID to particular people
                Then a LEFT JOIN is used in order to display all vehicles that have licence plates matching the search input,
                however not all vehicles have a specified owner, so LEFT JOIN is suitable since it will connect owners to vehicle
                where possible, but will still show the other vehicles (with NULL values in the People_name and People_licence fields)
                */
                $sql="SELECT Vehicle.Vehicle_ID, Vehicle_type, Vehicle_colour, Vehicle_licence, People_name, People_licence
                FROM Vehicle LEFT JOIN (SELECT People_name, People_licence, Vehicle_ID FROM Ownership, People 
                WHERE Ownership.People_ID=People.People_ID) AS S ON Vehicle.Vehicle_ID=S.Vehicle_ID WHERE Vehicle_licence LIKE '%".$_SESSION["vehicle"]."%';";
            }
            else // If the user is 'viewing all', select all rows
            {
                $sql="SELECT Vehicle.Vehicle_ID, Vehicle_type, Vehicle_colour, Vehicle_licence, People_name, People_licence
                FROM Vehicle LEFT JOIN (SELECT People_name, People_licence, Vehicle_ID FROM Ownership, People 
                WHERE Ownership.People_ID=People.People_ID) AS S ON Vehicle.Vehicle_ID=S.Vehicle_ID;";
            }
            $result=mysqli_query($conn,$sql);
        
            while ($row=mysqli_fetch_array($result))
            {
                $id = $row[0]; // store the user id number so that this can be used as a flag
                $link1="deletevehicleaction.php?flag=" . "$id"; // attach the user id number as a flag when the user clicks on the 'edit' or 'delete' button in each row
                $link2="editvehicle.php?flag=" . "$id";
                echo "<tr><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td><a href='$link2'>edit</a></td><td><a href='$link1'>delete</a></td></tr>";
            }
        
            if (mysqli_num_rows($result)==0)
            {
                header("Location: searchvehicle.php?flag=2"); // If there are no search matches, reload the page with a message stating this
            }        
            echo "
            </table>
            </section>
            ";
        }
        elseif($_GET["flag"]==2) // If flag is equal to 2, then there are no matches
        {
            echo "<p>Vehicle not in the system</p>";
        }
    }

	footer();
	?>
</html>
