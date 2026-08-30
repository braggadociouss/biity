<?php

session_start();
require("../database.php");
$profile_picture = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION["username"])) {
    if (isset($_POST["userName"]) && isset($_POST["password"])) {
        $un = $_POST["userName"];
        $password = hash("sha256", $_POST["password"]); 
        

        function userExists($usersdb, $username) {
            $stmt = $usersdb->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
            $count = 0;
            if (!$stmt) {
                customError("An error you cannot solve has occured: Malformed request");
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            return $count > 0;
        }

        if (userExists($usersdb, $un)) {
            echo "<script> window.location = '/signup.php?error=1&username=" . $un . "'</script>";
        } else {
            $stmt = $usersdb->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $un, $password);
            
            if ($stmt->execute()) {
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
                    echo "<script> window.location = '/signup.php?error=3' </script>";
                }
            } else {
                echo "<script> window.location = '/signup.php?error=2' </script>";
            }
            $stmt->close();
        }

        $usersdb->close();
    } else {
       customError("An error you cannot solve has occured: Malformed request");
        http_response_code(400);
    }
} else {
      customError("An error you cannot solve has occured: Malformed request");
     http_response_code(400);
}
?>

