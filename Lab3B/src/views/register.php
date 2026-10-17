<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <nav>
        <a href="https://www.youtube.com/watch?v=XfELJU1mRMg">Click Here!</a>
    </nav>
    <h1>Register</h1>
    <?php
        if (isset($_GET['nomatch'])) {
            echo "<div>ERROR: Passwords do not match</div>";
        }
        if (isset($_GET['baduser'])) {
            echo "<div>ERROR: Bad username</div>";
        }
    ?>
    <form id="user_registration" action="../actions/register_action.php" method="POST">
        <label for="username">Username: </label>
        <input type="text" id="username" name="Username" required class="text-input"/><br/>
        <label for="password">Password: </label>
        <input type="password" id="password" name="Password" required class="text-input"/><br/>
        <label for="confirm_password">Confirm Password: </label>
        <input type="password" id="confirm_password" name="ConfirmPassword" required class="text-input"/><br/>
        <button class = "add_user">Register</button>
    </form>
    <a href="./login.php">Already have an account? Log in!</a>
</body>
</html>