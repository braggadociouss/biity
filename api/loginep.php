<?php

session_start();
require("../database.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION["username"])) {
    if (isset($_POST["userName"]) && isset($_POST["password"])) {
        $un = $_POST["userName"];
        $password = hash("sha256", $_POST["password"]);
        $stmt = $usersdb->prepare('SELECT password FROM users WHERE username = ?');
        $correctpassword = "";
        if (!$stmt) {
            die('Prepare failed: (' . $usersdb->errno . ') ' . $usersdb->error);
        }
        $stmt->bind_param('s', $un);
        $stmt->execute();
        $stmt->bind_result($correctpassword);
        $stmt->fetch();
        $stmt->close();
        if ($password === $correctpassword){
                $stmt = $usersdb->prepare("SELECT user_id FROM users WHERE username = ?");
                $stmt->bind_param("s", $un);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['username'] = $un;
                        header("Location: /timeline.php");
                        exit();
                    }
                } else {
                    echo "<script> window.location = '/login.php?error=3' </script>";
                }
        } else {
                echo "<script> window.location = '/login.php?error=1' </script>";

        }
      
        $usersdb->close();
    } else {
        echo "<h1>Malformed request</h1>";
        http_response_code(400);
    }
} else {
    echo 'You can only use POST on this endpoint.';
}
?>

