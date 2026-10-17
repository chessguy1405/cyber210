<?php
    $mysql_servername = getenv("MYSQL_SERVERNAME");
    $mysql_user = getenv("MYSQL_USER");
    $mysql_password = getenv("MYSQL_PASSWORD");
    $mysql_database = getenv("MYSQL_DATABASE");
    $conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
    $find_task_sql = "SELECT * FROM task WHERE id = ?";
    $delete_task_sql = "DELETE FROM task WHERE id = ?";
    $task_id = intval($_POST['taskid']);

    // var_dump($task_id);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // $find_task = $conn->prepare($find_task_sql);
    // $find_task->bind_param('i', $task_id);
    // $find_task->execute();
    // $task = $find_task->get_result();
    // var_dump($task);

    $delete_task = $conn->prepare($delete_task_sql);
    $delete_task->bind_param('i', $task_id);
    $delete_task->execute();

    header("Location: /")
?>