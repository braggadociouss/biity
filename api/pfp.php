<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_SESSION["username"])){
    require("../database.php");
    $uploaddir = '/assets/user-uploads/profiles/';
    $uploadfile = $uploaddir . basename(uniqid());

    $old_picture = "";
    $stmt = $usersdb->prepare("SELECT profile_picture FROM users WHERE username = ?");
    $stmt->bind_param("s",$_SESSION["username"]);
    $stmt->execute();
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$fileType = mime_content_type($_FILES['new_profile_picture']['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    customError("Invalid file type.");
    goHome();
    exit;
}

if ($_FILES['new_profile_picture']['size'] > 100 * 1024 * 1024) {
    customError("File too large.");
    goHome();
    exit;
}
    $stmt->bind_result($old_picture);
    $stmt->fetch();
    $stmt->close();
    if ($old_picture !== "/assets/default-profile-pictures/goofy.jpg"){
        unlink($old_picture);
    }
    if (move_uploaded_file($_FILES['new_profile_picture']['tmp_name'], $uploadfile)) {
        $stmt = $usersdb->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
        $stmt->bind_param("ss", $uploadfile, $_SESSION["username"]);
        $stmt->execute();
        customError("Successfully changed your profile picture.");
    } else {
     //   customError("Unknown error! Try again");
    }
}
goHome();