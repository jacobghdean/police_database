<?php
require __DIR__ . '/functions.php';
verify();
menu();
$conn = mysqli_connect($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"], $_SESSION["dbname"]);
?>

<html>
    
<head>
<title>Search Person</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css"> 
</head>

<header> <!-- Add three hyperlink buttons that take you to the 'add person' page, view all people, or clear search results respectively -->
<p><a href="addperson.php"><i>Add Person</i></a><a href="searchperson.php?flag=4"><i>View All</i></a></p><a href="searchperson.php?"><b>Clear Results</b></a><p>
</header>

    <section> <!-- Add a form that allows the user to search for a person by name or driving licence -->
    <form action="searchperson.php?flag=1" method="post">
    <header>
        <h2>Search Person</h2>
    </header>
        <label for="person">Enter name or driving licence:</label>
        <input type="text" name="person"><br/>
        <button type="submit">Submit</button>
    </form>
    </section>

    <?php
    if ($_GET)
    {
        if ($_GET["flag"]==1 || $_GET["flag"]==3 || $_GET["flag"]==4) // If the flags take these values, we proceed with the search
        {
            echo "
            <section>
            <table>
            <thead>
            <tr><th>People ID</th><th>Name</th><th>Address</th><th>Licence</th><th></th><th></th></tr>
            </thead>
            ";
            
            if ($_GET["flag"]==1) // If the user filled in the form, store their input
            {
                $_SESSION["person"]=$_POST["person"];
            }
            if ($_GET["flag"]==4)
            {
                $_SESSION["person"]=""; // Clear the search input if the user clicks the 'view all' button: prevents bug where you try to search, then view all then delete but it stores and shows the searched rather than view all version
            }
            if($_GET["flag"]!=4) // If the user is not 'viewing all', select all rows from the 'People' table that match their search input
            {
                // This query looks for rows where name or driving licence contain the characters specified the user in their search input
                $sql = "SELECT * FROM People WHERE People_name LIKE '%".$_SESSION["person"]."%' OR People_licence LIKE '%".$_SESSION["person"]."%';";
            }
            else // If the user is 'viewing all', simply select all rows from the 'People' table
            {
                $sql = "SELECT * FROM People;";
            }
            $result=mysqli_query($conn,$sql);
        
            while ($row=mysqli_fetch_array($result)) // Once the query has been completed, pipe all data into a table
            {   
                $id = $row[0]; // store the user id number so that this can be used as a flag
                $link1="deletepersonaction.php?flag=" . "$id"; // attach the user id number as a flag when the user clicks on the 'edit' or 'delete' button in each row
                $link2="editperson.php?flag=" . "$id";
                // This enters each row of data as a row into the table, with two additional columns to contain 'delete' and 'edit' buttons
                echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td><a href='$link2'>edit</a></td><td><a href='$link1'>delete</a></td></tr>";
            }
        
            if (mysqli_num_rows($result)==0)
            {
                header("Location: searchperson.php?flag=2"); // If there are no search matches, reload the page with a message stating this
            }		
               
            echo "
            </table>
            </section>
            ";
        }
        elseif ($_GET["flag"]==2) // If flag is equal to 2, then there are no matches
        {
            echo "<p>Person not in the system</p>";
        }
        
    }
	
	footer();
	?>

</html>
