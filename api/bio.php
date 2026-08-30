<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_SESSION["username"])){
    require("../database.php");
    $bio = htmlspecialchars($_POST["bio"]);
    $username = $_SESSION["username"];
    $stmt = $usersdb->prepare("UPDATE users SET bio = ? WHERE username = ?;");
    $stmt->bind_param("ss", $bio, $username);
    $stmt->execute();
    $stmt->close();
}
header("Location: ../settings.php");
exit;
