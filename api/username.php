<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_SESSION["username"])){
    require("../database.php");
    $stmt = $usersdb->prepare("UPDATE users SET username = ? WHERE username = ?");
    $stmt->bind_param("ss", $_POST["newusername"], $_SESSION["username"]);
    $stmt->execute();
    $_SESSION["username"] = $_POST["newusername"];
}
customError("Successfully changed your username.");
