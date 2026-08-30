<?php
session_start();
require("../database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/main.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/pfp.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/login.css'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect   " href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap" rel="stylesheet">
    <script src='/assets/js/main.js' defer></script>
    <title>Change your username</title>
</head>
<body>
    <div class="container">
        <h2>Change your username</h2>
        <form action="/api/username.php" method="post" enctype="multipart/form-data">
            <div>
                <label for="newusername">Choose your new username:</label>
                <br>
                <input type="text" name="newusername" id="newusername" required>
            </div>
            <button type="submit">Update Username</button>
        </form>
    </div>
</body>
</html>
