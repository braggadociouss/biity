<?php
session_start();
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]))
{
    echo "<script>window.location.href = 'timeline.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>biity</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/main.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/home.css'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap" rel="stylesheet">
    <script src='assets/js/main.js' defer></script>
</head>
<body>
    <div class="wrapper-hero-main">
        <div class="title-wrapper">
            <h1 class="title">biity<br></h1>
        </div>
        <div class="middleman-3dot">...</div>
        <div class="desc">
            <p>
                A basic, fundamental social media website with image/video post support, commenting, and liking. 
            </p>          
        </div>
        <div class="calltoaction-hero">
            <div class="button-ctah-wrp">
                <div class="buttons">
                    <a href="./signup.php" class="button cta-button signup-btn">Sign up</a>
                    <div class="mdl-or">or</div>
                    <a href="./login.php" class="button cta-button signup-btn">Log in</a>
               </div>

            </div>
        </div>

    </div>
</body>
</html>
