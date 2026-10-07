<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <nav>
        <a href="https://www.youtube.com/watch?v=XfELJU1mRMg">Click Here!</a>
    </nav>
    <h1>Login</h1>
    <?php
    if (isset($_GET['failed'])) {
        echo("<div>Login Failed: Invalid Username or Password</div>");
    }
    ?>
    <form id="user_login" action="../actions/login_action.php" method="POST">
        <label for="username">Username: </label>
        <input type="text" id="username" name="Username" required class="text-input"/><br/>
        <label for="password">Password: </label>
        <input type="password" id="password" name="Password" required class="text-input"/><br/>
        <button class = "login">Login</button>
    </form>
    <a href="./register.php">New? Register Here!</a>
</body>
</html>