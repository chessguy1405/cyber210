<?php
    $mysql_servername = getenv("MYSQL_SERVERNAME");
    $mysql_user = getenv("MYSQL_USER");
    $mysql_password = getenv("MYSQL_PASSWORD");
    $mysql_database = getenv("MYSQL_DATABASE");
    $conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
    $get_task_sql = "SELECT * FROM task WHERE id = ?";
    $update_task_sql = "UPDATE task SET done = ? WHERE id = ?";
    $task_id = intval($_POST['taskid']);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

        $get_task = $conn->prepare($get_task_sql);
    $get_task->bind_param('i', $task_id);
    $get_task->execute();
    $task = $get_task->get_result();
    $something = $task->fetch_assoc();
    $is_done = $something['done'];

    if ($is_done == 0) {
        $done = 1;
    } else {
        $done = 0;
    }

    $update_task = $conn->prepare($update_task_sql);
    $update_task->bind_param('ii', $done, $task_id);
    $update_task->execute();

    header("Location: /");
?>