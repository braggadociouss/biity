<?php

session_start();

if (isset($_SESSION["username"], $_SESSION["user_id"])) {
    header("Location: timeline.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/signup.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap"
        rel="stylesheet"
    >

    <script src="assets/js/signup.js" defer></script>

    <title>Sign up</title>
</head>

<body>

<?php
if (isset($_GET["error"])) {
    if ($_GET["error"] == 1 && isset($_GET["username"])) {
        echo "<h3><span style='color: red;'>The username '" .
            htmlspecialchars($_GET["username"]) .
            "' is in use. Select a different name.</span></h3>";
    } elseif ($_GET["error"] == 2) {
        echo "<h3><span style='color: red;'>The database failed to update. Is the server having issues?</span></h3>";
    } else {
        echo "<h3><span style='color: red;'>Unknown error (are you URL tampering?)</span></h3>";
    }
}
?>

    <h1>Sign up</h1>

    <div class="signup-wrapper">
        <form method="POST" action="/api/createNewUser.php">
            <div class="signup-username-wrapper">
                <span class="username-form">Username:</span>
                <input
                    type="text"
                    name="userName"
                    class="inputfield"
                    required
                >
            </div>

            <div class="signup-password-wrapper">
                <span class="username-form">Password:</span>
                <input
                    type="password"
                    name="password"
                    class="inputfield"
                    required
                >
            </div>

            <input type="submit" value="Sign up">
        </form>
    </div>

</body>

</html>
