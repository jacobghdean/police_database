<?php

function verify($admin=False) // This function will be called on each page to check if the user has logged in and if they are an administrator
{
    session_start();

    if ($_SESSION["Check"]==0) // This session variable is zero when the user is not logged in
    {
        header("Location: login.php"); // Redirection to login page from all other pages if not logged in
    }

    if ($admin) // True if the page should be restricted to admin users
    {
        if ($_SESSION["Permissions"]=='Regular')
        {
            header("Location: home.php"); // If the user is not an admin page, they are redirected to the home page if they try to access an admin-restricted page
        }
    }
}

function menu($admin=False) // This function will be called on each page to display the menu bar
{
/* 
Adds the police logo in the top left as a hyperlink back to the home page
Creates drop down menus that include links to the different database 'searching' and 'adding' functionalities
Includes an extra drop down menu that displays if the administrator account logs in
*/
    echo '
    <header>
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
            </li>';
            
            if ($_SESSION["Permissions"]=="Administrator")
            {
            echo '
            <li><a href="">Admin Functions</a>
                <ul>
                    <li><a href="addfine.php">Add Fine</a></li>
                    <li><a href="addtask.php">Add Task</a></li>
                    <li><a href="createaccount.php">Create Account</a></li>
                </ul>
            </li>';
            }
            
            echo '
            <li><a href="">My Account</a>
                <ul>
                    <li><a href="changepassword.php">Change Password</a></li>
                    <li><a href="login.php?flag=2">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    </header>';
}

function footer() // This function will be called on every page to display the footer
{
    echo '
    <footer>
    <hr>
    <article>
        <small>@Nottinghamshire Police Department</small>
    </article>
    </footer>
    ';
}
?>