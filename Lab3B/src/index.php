<?php
error_reporting(-1);
session_start();

$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
$logged_in_sql = "SELECT logged_in FROM user WHERE username = ?";
$get_user_id_sql = "SELECT id FROM user WHERE username = ?";
$fetch_tasks_sql = "SELECT * FROM task WHERE user_id = ?";
$sort_checked = '';
$filter_checked = '';

if (!isset($_SESSION['sorted'])) {
    $_SESSION['sorted'] = false;
}
if (!isset($_SESSION['filtered'])) {
    $_SESSION['filtered'] = false;
}


if ($_SESSION['filtered']) {
    $fetch_tasks_sql = $fetch_tasks_sql . " AND done = 0";
    $filter_checked = $filter_checked . 'checked';
}


if ($_SESSION['sorted']) {
    $fetch_tasks_sql = $fetch_tasks_sql . " ORDER BY `date`";
    $sort_checked = $sort_checked . 'checked';
}

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

function get_user_id() {
    global $conn;
    global $get_user_id_sql;
    $user_id_process = $conn->prepare($get_user_id_sql);
    $user_id_process->bind_param('s',  $_SESSION['username']);
    $user_id_process->execute();
    $user_rows = $user_id_process->get_result();
    $user_row = $user_rows->fetch_assoc();
    return($user_row['id']);
}

function read_tasks() {
    global $conn;
    global $fetch_tasks_sql;
    $user_id = get_user_id();
    $task_records =  $conn->prepare($fetch_tasks_sql);
    $task_records->bind_param('s', $user_id);
    $task_records->execute();
    $task_rows = $task_records->get_result();

    $task_list = '<ul class="tasklist" id="tasklist">';
    while ($task_row = $task_rows->fetch_assoc()) {
        $checked = '';
        if ($task_row['done'] == 1) {
            $checked = 'checked';
        }
        $date = date('m/d/Y', strtotime($task_row['date']));
        $task_list = $task_list . "
            <li class='task'\>
            <form action='./actions/update_action.php' method='POST' class='task-form'>
            <input type='hidden' name='taskid' value='{$task_row['id']}'>
            <input type='checkbox' {$checked} class='task-done checkbox-icon' onchange='this.form.submit()'/>
            </form>
            <span class='task-description'>{$task_row['text']}</span>
            <span class='task-date'>{$date}</span>
            <form action='./actions/delete_action.php' method='POST' class='task-form'>
            <input type='hidden' name='taskid' value='{$task_row['id']}'>
            <button class='task-delete material-icon'>delete</button>
            </form>
            </li>
            ";
    }

    $task_list = $task_list . '</ul>';

    return($task_list);
}

function make_options() {
    global $sort_checked;
    global $filter_checked;

    $resulting_html = "
    <form class='task-form' action='./actions/sort_action.php'>
    <input type='checkbox' {$sort_checked} id='cb-sort' class='toggle-switch' onchange='this.form.submit()'/>
    <label for='cb-sort'>Sort by Date</label>
    </form>
    <form class='task-form' action='./actions/filter_action.php'>
    <input type='checkbox' {$filter_checked} id='cleanup' class='toggle-switch' onchange='this.form.submit()'/>
    <label for='cleanup'>Filter completed tasks</label>
    </form>
    ";

    return($resulting_html);
}

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

$first_half_of_page = '
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
';

$option_buttons = make_options();

$second_half_of_page = '
    <form id="new-task" action="./actions/create_action.php" method="POST">
        <input type="hidden" name="userid" value="' . get_user_id() . '"/><br/>
        <input type="text" name="description" required class="text-input"/><br/>
        <input type="date" name="date" required/><br/>
        <button class = "create-task">Create Task</button>
    </form>
</body>
</html>
';

$tasklist = read_tasks();

$dom = $first_half_of_page . $option_buttons . $tasklist . $second_half_of_page;

if ($_SESSION['logged_in'] == 1) {

    echo($dom);
} else {
    header("Location: /views/login.php");
    exit;
}
?>