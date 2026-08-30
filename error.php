<?php 
    $error = htmlspecialchars($_SERVER['REDIRECT_STATUS']);
?>

<html>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap" rel="stylesheet">
        <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/main.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/home.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/signout.css'>
        <title>Error <?php echo $error; ?></title>
    </head>
    <body>
        <center>
            <h1><?php echo $error; ?></h1>
            <a href="/index.php" class="gohome-button">Go Home</a>
        </center>
    </body>
</html>

