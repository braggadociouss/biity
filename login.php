<?php 
session_start();
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]))
{
    echo "<script>window.location.href = 'timeline.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/main.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/signup.css'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap" rel="stylesheet">
    <script src='assets/js/main.js' defer></script>
    <title>Log in</title>
</head>
<body>
    <?php 
     if (isset($_GET["error"])){
        if ($_GET["error"] == 1){
            echo "<h3><span style='color: red;'>Invalid password</span></h3>";
        } elseif ($_GET["error"] == 2){
            echo "<h3><span style='color: red;'>No user with that name found!</span></h3>";
        } else{
            echo "<h3><span style='color: red;'>Unknown error</span></h3>";
 
        }
     }
    ?>
 <h1>biity - log in</h1>

    <div class="signup-wrapper">
        <form method="POST" action="/api/loginep.php">
            <div class="signup-username-wrapper">
                <span class="username-form">username:</span>
                <input type="text" name="userName" class="inputfield" required>
            </div>
            <div class="signup-password-wrapper">
                <span class="username-form">password:</span>
                <input type="password" name="password" class="inputfield" required>
            </div>
            <input type="submit" value="Log in">
        </form>
    </div>
</body>
</html>
