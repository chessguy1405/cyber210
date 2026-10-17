<?php
    $mysql_servername = getenv("MYSQL_SERVERNAME");
    $mysql_user = getenv("MYSQL_USER");
    $mysql_password = getenv("MYSQL_PASSWORD");
    $mysql_database = getenv("MYSQL_DATABASE");
    $conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
    $new_task_sql = "INSERT INTO task (user_id, text, date, done) VALUES(?,?,?,?)";
    $userid = intval($_POST['userid']);
    $task_text = $_POST['description'];
    $task_date = $_POST['date'];
    $done = 0;

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $insert_task = $conn->prepare($new_task_sql);
    $insert_task->bind_param('issi', $userid, $task_text, $task_date, $done);
    $insert_task->execute();

    header("Location: ../index.php");
?>