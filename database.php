<?php
// Finished on March 28th
require '/var/www/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable("/var/www");
$dotenv->load();
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
function customError($error){
        echo "<script> window.location = '/error.php?error=" . $error . "'</script>";
}
function goHome(){
        echo "<script> window.location = '/index.php'";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_METHOD'] === "GET") {
   exit("Unknown error"); 
}
$usersdb = new mysqli("localhost", $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], "userdata");

if ($usersdb->connect_error) {
    die("Connection failed: " . $usersdb->connect_error);
}
// I'm lazy so instead of manually adding a new file called favicon.php i just added it here LMAO
?>
<head>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
</head>