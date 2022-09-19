<?php
require __DIR__ . '/functions.php';
verify($admin=True); // We specify the 'admin' parameter as True here to ensure that non-admins cannot access this feature
menu();
?>

<html>
<head>
<title>Add Task</title>
<style>html{visibility: hidden;opacity:0;}</style>
<link rel="stylesheet" href="mvp.css">

<script>
function ask() // Ask for confirmation before adding task
{
    text = "Would you like to add this task?\nSelect OK or Cancel.";
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

<section> <!-- Form where the administrator can fill in the task details -->
    <form onsubmit="return ask()" action="addtaskaction.php" method="post">
    <header>
    <h2>Add New Task</h2>
    </header>
    <label for="description">Task Description:*</label>
    <input type="text" name="description" required><br/> <!-- 'description' and 'officer' are both required fields -->
    <label for="officer">Officer Responsible (Username):*</label>
    <input type="text" name="officer" required><br/>
    <i>* Required Field</i><br/><br/>
    <button type="submit">Submit</button>
    </form>
</section>

<?php
if ($_GET) // If we redirect to this page with a flag in the URL, show validation that the new task has been added
{
    echo "<p>New Task added</p>";
}
?>
</html>