<?php
session_start(); // This finds and saves session variables
?>

<html>
	<head>
		<title>Portal Login</title> <!-- This controls the name of the web tab -->
		<style>html{visibility: hidden;opacity:0;}</style> <!-- This controls the name of the web tab -->
		<link rel="stylesheet" href="mvp.css"> <!-- Connects this page to the css page -->

		<script>
        function validateForm() // Javascript form validation (reference: https://www.w3schools.com/js/js_validation.asp)
        {
            var x = document.forms["Login Form"]["user"].value; // Collects username from the login form
            if (x == "") 
			{
                alert("Username must be filled out"); // Gives a pop-up alert if username is an empty string
                return false; // Do not submit form if the username is empty
            }
            var y = document.forms["Login Form"]["pass"].value; // Collects password from the login form
            if (y == "") 
			{
                alert("Password must be filled out");
                return false;
            }
        }
    	</script>
	</head>

	<body>
		<main>

			<section> <!-- The 'section' divider is set in mvp.css to allow separate elements to be centred properly -->
				<form name="Login Form" onsubmit="return validateForm()" action="loginaction.php" method="post"> <!-- Creates a HTML form and stores the inputted data using the 'post' method -->
				<header>
					<h2>Portal Login</h2>
				</header>	
				<label for="user">Username:</label> <!-- Looks for two inputs that will be stored under the variable names 'username' and 'password' -->
				<input type="text" name="user"><br/>
				<label for="user">Password:</label>
				<input type="password" name="pass"><br/>
				<button type="submit">Submit</button> <!-- Creates a submit button, when this is clicked the page is reloaded and the php file is rerun -->
				</form>
			</section>

			<?php
			if ($_GET) // Depending on the 'flags' in the URL a validation message will appear
			{
				if ($_GET["flag"]==1)
				{
					echo "<p>Incorrect username or password</p>";
				}
				elseif ($_GET["flag"]==2)
				{
					echo "<p>Logout Successful</p>";
					session_destroy(); // If you have logged out, destroy all session variables before anyone logs in again
				}
			}
			?>
		</main>
	</body>
</html>