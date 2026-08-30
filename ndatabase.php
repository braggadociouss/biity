<?php

require "/var/www/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable("/var/www");

$dotenv->load();

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    $_SERVER["REQUEST_METHOD"] === "GET"
) {
    exit("Unknown error");
}

$usersdb = new mysqli(
    "localhost",
    $_ENV["MYSQL_USER"],
    $_ENV["MYSQL_PASSWORD"],
    "userdata"
);

if ($usersdb->connect_error) {
    die("Connection failed: " . $usersdb->connect_error);
}
