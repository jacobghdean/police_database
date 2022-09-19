<?php
require __DIR__ . '/functions.php';
verify();
$conn=mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);
?>

<html>
<head>
<title>Home</title>
<style>html{visibility: hidden;opacity:0;}</style> <!-- Sets content as invisible until page has fully rendered to prevent the flash of unstyled content (Reference: https://stackoverflow.com/questions/3221561/eliminate-flash-of-unstyled-content/43823506) -->
<link rel="stylesheet" href="mvp.css"> <!-- https://andybrewer.github.io/mvp/ used as a foundation for formatting the website-->
</head>

<header> <!-- This is a copy of the menu() function in functions.php so that we can insert extra elements into the header here -->
    <nav>
        <a href="home.php"><img alt="Logo" src="police_logo.png" height="70"></a>
        <ul>
            <li><a href="">Search Records</a>
                <ul>
                    <li><a href="searchperson.php">Search Person</a></li>
                    <li><a href="searchvehicle.php">Search Vehicle</a></li>
                    <li><a href="searchreport.php">Search Incident</a></li>
                </ul>
            </li>
            <li><a href="">Add Records</a>
                <ul>
                    <li><a href="addperson.php">Add Person</a></li>
                    <li><a href="addvehicle.php">Add Vehicle</a></li>
                    <li><a href="addreport.php">Add Incident</a></li>
                </ul>
            </li>
            <?php
            if ($_SESSION["Permissions"]=="Administrator")
            {
            echo '
            <li><a href="">Admin Functions</a>
                <ul>
                    <li><a href="addfine.php">Add Fine</a></li>
                    <li><a href="addtask.php">Add Task</a></li>
                    <li><a href="createaccount.php">Create Account</a></li>
                </ul>
            </li>
            ';
            }
            ?>
            <li><a href="">My Account</a>
                <ul>
                    <li><a href="changepassword.php">Change Password</a></li>
                    <li><a href="login.php?flag=2">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

        <!-- MY DASHBOARD -->

        <h1>My Dashboard</h1>
        <p>Real-time insights</p>
        </br>
        <section>

            <aside> <!-- The 'aside' tag will create callout boxes in the mvp.css styling -->
                <h3>Top Incident Types</h3> <!-- Creating a mini-table containing the most common types of incidents -->
                <p>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Offence</th><th>Count</th></tr>
                    </thead>
                    <?php
                        // This SQL performs an inner join then counts rows based on matching incidents then selects the top three of these rows
                        $sql="SELECT Offence.Offence_ID, Offence_description, COUNT(*) FROM Incident, Offence 
                        WHERE Incident.Offence_ID=Offence.Offence_ID 
                        GROUP BY Incident.Offence_ID ORDER BY COUNT(*) DESC LIMIT 3;";
                        $result=mysqli_query($conn,$sql);
                        while ($row=mysqli_fetch_array($result)) // While loop will pipe the results from the SQL query into the table
                        {   
                            echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
                        }
                    ?>
                </table>
                </p>
            </aside>

            <aside>
                <h6>
                <?php
                    // This SQL counts the number of incidents that took place since the start of 2017 (when the incident records start);
                    $sql="SELECT COUNT(*) FROM Incident WHERE Incident_Date > '2016-12-31';";
                    $result=mysqli_query($conn,$sql);
                    $total=mysqli_fetch_array($result)[0];
                    echo $total;
                ?>    
                </h6>
                <p>
                Total Incidents since 2017
                </p>
                </br>
            </aside>

            <aside>
                <h3>Worst Offenders</h3> <!-- Creating a table that displays the top three individuals who have completed the most offences -->
                <p>
                <table>
                <thead>
                    <tr><th>ID</th><th>Full Name</th><th>Count</th></tr>
                </thead>
                <?php
                    $sql="SELECT People.People_ID, People_name, COUNT(*) FROM Incident, People
                    WHERE Incident.People_ID=People.People_ID 
                    GROUP BY Incident.People_ID ORDER BY COUNT(*) DESC LIMIT 3;";
                    $result=mysqli_query($conn,$sql);
                    while ($row=mysqli_fetch_array($result))
                    {   
                        echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
                    }
                ?>
                </table>
                </p>
            </aside>
        </section>
    </header>
    
    <main>
        <hr>

        <!-- QUICK SEARCH -->

        <section> <!-- section tag is programmed in mvp.css to centre elements -->
            <header>
                <h2>Quick Search</h2> <!-- This form will allow users to display entries from a table of their choice -->
                <p>Search for any entries in any table</p>
            </header>
        </section>

        <section>
            <form action="home.php" method="post">
                <label for="char">Search characters:</label>
                <input type="text" name="char">
                <label for="table">In table:</label>
                <select name="table"> <!-- Drop down list of tables to choose from -->
                    <option value="People">People</option>
                    <option value="Vehicle">Vehicle</option>
                    <option value="Incident">Incident</option>
                </select>
                <button type="submit">Submit</button>
                <button type="button" onclick="location.href='home.php'">Clear</button>
            </form>
        </section>
        <?php
            if (isset($_POST["table"])) // Enters this conditional if the user has selected a table to search from
            {
                $table=$_POST["table"];
                $char=$_POST["char"];

                if ($table=="People") // Depending on the table that the user has selected, print the results from a particular SQL query
                {
                    echo "
                    <section>
                    <table>
                    <thead>
                    <tr><th>People ID</th><th>Full Name</th><th>Address</th><th>Driving Licence</th></tr>
                    </thead>
                    ";
                    // This query will select all columns from the 'People' table and displays all rows where any entry matches the characters specified by the user in the search box
                    $sql="SELECT * FROM People WHERE People_name LIKE '%$char%' OR People_address LIKE '%$char%' OR People_licence LIKE '%$char%';";
                    $result=mysqli_query($conn,$sql);
                    while ($row=mysqli_fetch_array($result))
                    {
                        echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>";
                    }
                    echo "
                    </table>
                    </section>
                    ";
                }
                elseif ($table=="Vehicle")
                {
                    echo "
                    <section>
                    <table>
                    <thead>
                    <tr><th>Vehicle ID</th><th>Type</th><th>Colour</th><th>Licence Plate</th></tr>
                    </thead>
                    ";
                    // This query will select all columns from the 'Vehicle' table and displays all rows where any entry matches the characters specified by the user in the search box
                    $sql="SELECT * FROM Vehicle WHERE Vehicle_type LIKE '%$char%' OR Vehicle_colour LIKE '%$char%' OR Vehicle_licence LIKE '%$char%';";
                    $result=mysqli_query($conn,$sql);
                    while ($row=mysqli_fetch_array($result))
                    {
                        $id = $row[0];
                        $link1="deletevehicleaction.php?flag=" . "$id";
                        $link2="editvehicle.php?flag=" . "$id";
                        echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>";
                    }
                    echo "
                    </table>
                    </section>
                    ";
                }
                elseif ($table=="Incident")
                {
                    echo "
                    <section>
                    <table>
                    <thead>
                    <tr><th>Incident ID</th><th>Vehicle ID</th><th>People ID</th><th>Date</th><th>Report</th><th>Offence ID</th></tr>
                    </thead>
                    ";
                    // This query will select all columns from the 'Incident' table and displays all rows where any entry matches the characters specified by the user in the search box
                    $sql="SELECT * FROM Incident WHERE Vehicle_ID LIKE '%$char%' OR People_ID LIKE '%$char%' OR Incident_Date LIKE '%$char%' OR Incident_Report LIKE '%$char%' OR Offence_ID LIKE '%$char%';";
                    $result=mysqli_query($conn,$sql);
                    while ($row=mysqli_fetch_array($result))
                    {
                        echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td></tr>";
                    }
                    echo "
                    </table>
                    </section>
                    ";
                }
            }
        
            /* MY TASKS */

            if ($_SESSION["Permissions"]=="Regular") // Show the tasks set by the admin if the user is logged in as a regular account
            {
                echo '
                <hr>
                <header>
                <h2>My Tasks</h2>
                <p>View and complete assigned tasks</p>
                </header>
                <article>
                <ul>
                ';

                $sql="SELECT UserID FROM Users WHERE Username='".$_SESSION["Person"]."';"; // First, find the ID of the user logged in
                $result=mysqli_query($conn,$sql);
                $index=mysqli_fetch_array($result)[0];

                $sql="SELECT TaskID, Task_Description FROM Tasks WHERE UserID='$index';"; // Next, collect all the tasks for the user logged in using their ID
                $result=mysqli_query($conn,$sql);
                while ($row=mysqli_fetch_array($result)) // Finally, pipe the rows from this query as bullet points onto the home page
                {
                    $id = $row[0];
                    $link1="deletetaskaction.php?flag=" . "$id"; // Allows the user to click on the 'complete' button to delete a task
                    echo "<li>$row[1] <a href='$link1'>- complete</a></li>";
                }

                echo '
                </ul>
                </article>';

            }

    echo '</main>';

	footer(); // Displays the footer as specified in functions.php
	?>
</html>