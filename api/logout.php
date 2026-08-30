<?php 
session_start();
$_SESSION["username"] = null;
$_SESSION["user_id"]  = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/main.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/signout.css'>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap" rel="stylesheet">
    <title>Signed out</title>
</head>
<body>
    <h1>You have been signed out. You can now close this page.</h1>
    <a href="/index.php" class="gohome-button">Go Home</a>
</body>
</html>
