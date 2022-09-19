<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);
?>

<html>

<head>
<title>Search Incident</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 
</head>

<header> <!-- Add three hyperlink buttons that take you to the 'add incident' page, view all people, or clear search results respectively -->
<p><a href="addreport.php"><i>Add Incident</i></a><a href="searchreport.php?flag=4"><i>View All</i></a></p><a href="searchreport.php?"><b>Clear Results</b></a><p>
</header>

    <section> <!-- Add a form that allows the user to search for an incident by date or report text -->
        <form action="searchreport.php?flag=1" method="post">
        <header>
            <h2>Search Incident</h2>
        </header>
            <label for="report">Enter date or report text:</label>
            <input type="text" name="report"><br/>
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
            <tr><th>Incident ID</th><th>Vehicle ID</th><th>People ID</th><th>Incident Date</th><th>Report</th><th>Offence_ID</th><th></th><th></th></tr>
            </thead>
            ";
            
            if ($_GET["flag"]==1) // If the user filled in the form, store their input
            {
                $_SESSION["report"]=$_POST["report"];
            }    
            if ($_GET["flag"]==4)
            {
                $_SESSION["report"]=""; // Clear the search input if the user clicks the 'view all' button: prevents bug where you try to search, then view all then delete but it stores and shows the searched rather than view all version
            }
            if($_GET["flag"]!=4)
            {
                // This query looks for rows where incident report or date contain the characters specified the user in their search input
                $sql = "SELECT * FROM Incident WHERE Incident_Report LIKE '%".$_SESSION["report"]."%' OR Incident_Date LIKE '%".$_SESSION["report"]."%';";
            }
            else // If the user is 'viewing all', simply select all rows from the 'Incident' table
            {
                $sql = "SELECT * FROM Incident;";
            }
            $result=mysqli_query($conn,$sql);
            
            while ($row=mysqli_fetch_array($result)) // Once the query has been completed, pipe all data into a table
                {
                    $id = $row[0]; // store the user id number so that this can be used as a flag
                    $link1="deletereportaction.php?flag=" . "$id"; // attach the user id number as a flag when the user clicks on the 'edit' or 'delete' button in each row
                    $link2="editreport.php?flag=" . "$id";
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td><a href='$link2'>edit</a></td><td><a href='$link1'>delete</a></td></tr>";
                }

            if (mysqli_num_rows($result)==0)
            {
                header("Location: searchreport.php?flag=2"); // If there are no search matches, reload the page with a message stating this
            }

            echo "
            </table>
            </section><br/>
            ";
                    
        }
        if ($_GET["flag"]==2) // If flag is equal to 2, then there are no matches
        {
            echo "<p>Report is not in system</p>";
        }
        /*elseif ($_GET["flag"]==2)
        {
            echo "<p>Incident updated</p>";
        }*/
    }
    
	footer();
	?>
</html>