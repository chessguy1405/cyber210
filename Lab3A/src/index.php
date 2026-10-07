<?php
error_reporting(-1);
session_start();

if(!isset($_SESSION['username'])) {
    $_SESSION['username'] = ""
}

$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
$logged_in_sql = "SELECT logged_in FROM user WHERE username = ?";

$is_logged_in = $conn->prepare($logged_in_sql);
$is_logged_in->bind_param('s', $_SESSION['username']);
$is_logged_in->execute();
$logged_in_rows = $is_logged_in->get_result();
if ($logged_in_rows->num_rows == 0) {
	header("Location: /views/login.php");
	exit;
}
$row = $logged_in_rows->fetch_assoc();
$_SESSION['logged_in'] = $row['logged_in'];

if ($_SESSION['logged_in'] == 1) {

    echo('
        <!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>CYBER 210 Tasks</title>
            <link rel="stylesheet" href="./css/style.css">
        </head>

        <body>
            <!-- Your visible elements -->
            <nav>
                <a href="https://www.youtube.com/watch?v=XfELJU1mRMg">Click Here!</a>
                <a href="./actions/logout_action.php">Logout</a>
            </nav>
            <h1>My Tasks</h1>
            <input type="checkbox" unchecked id="cb-sort" class="toggle-switch" onchange="toggleSort()"/>
            <label for="cb-sort">Sort by Date</label>
            <input type="checkbox" unchecked id="cleanup" class="toggle-switch" onchange="toggleFilter()"/>
            <label for="cleanup">Filter completed tasks</label>
            <button class="reload-tasks" onclick="readTasks()">Reload Tasks</button>
            <ul class="tasklist" id="tasklist">
                <li class="task">
                    <input type="checkbox" checked class="task-done checkbox-icon"/>
                    <span class="task-description">This is a task</span>
                    <span class="task-date">09-02-2021</span>
                    <button class="task-delete material-icon">delete</button>
                </li>
                <li class="task">
                    <input type="checkbox" class="task-done checkbox-icon"/>
                    <span class="task-description">This could also be a task if you want</span>
                    <span class="task-date">09-05-2029</span>
                    <button class="task-delete material-icon">delete</button>
                </li>
            </ul>
            <form id="new-task" onsubmit="createTask(event)">
                <input type="text" name="description" required class="text-input"/><br/>
                <input type="date" name="date" required/><br/>
                <button class = "create-task">Create Task</button>
            </form>
            <!-- Links to scripts -->
            <script src="js/script.js"></script>
        </body>
        </html>
        <p>You can also use normal tags outside of any PHP blocks.</p>');
}

?>