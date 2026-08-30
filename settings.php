<?php

session_start();

require("./database.php");

$profile_picture = null;

if (isset($_SESSION["username"])) {
    $stmt = $usersdb->prepare(
        "SELECT profile_picture FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_picture = $row["profile_picture"];
    }

    $stmt->close();
} else {
    header("Location: index.php");
    exit;
}

$usersdb->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Account settings</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/settings.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap"
        rel="stylesheet"
    >

    <script src="assets/js/main.js" defer></script>
    <script src="assets/js/navbar.js" defer></script>
</head>

<body>

    <nav class="navbar">
        <a class="navbar-logo" href="/index.php">
            <img
                alt="biity"
                src="/assets/img/biity.png"
            >
        </a>

        <div class="navbar-links">
            <a href="/timeline.php" class="navbtn">Timeline</a>
            <a href="/users.php" class="navbtn">Users</a>
        </div>

        <div class="navbar-profile">
            <?php
            if (isset($_SESSION["username"], $_SESSION["user_id"])) {
                echo "<span class='welcome-msg'>Hello, " .
                    htmlspecialchars($_SESSION["username"]) .
                    "!</span>";

                if ($profile_picture) {
                    echo "<div class='dropdown'>";

                    echo "<img
                        class='profile-pic'
                        src='" . htmlspecialchars($profile_picture) . "'
                        alt='Your profile picture'
                        onclick='toggleDropdown()'
                    >";

                    echo "<div class='dropdown-content' id='dropdown-menu'>";

                    echo "<a href='/newpost.php'>New Post</a>";
                    echo "<a href='/api/logout.php'>Log Out</a>";

                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<a href='/login.php' class='navbtn'>Sign In</a>";
            }
            ?>
        </div>
    </nav>

    <center>
        <h1>Account settings</h1>

        <div class="profile-picture">
            <a
                href="/acntmngmnt/newProfilePicture.php"
                class="changepfp"
            >
                <img
                    class="bigimg"
                    src="<?= htmlspecialchars($profile_picture) ?>"
                    alt="Profile Picture"
                >
            </a>

            <br>

            <a
                href="acntmngmnt/changeUsername.php"
                class="dontlikethat"
            >
                Your username is
                <?= htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8") ?>
            </a>

            <h1>Change your bio</h1>

            <form
                action="/api/bio.php"
                method="post"
                enctype="multipart/form-data"
            >
                <textarea name="bio" id="bio"></textarea>

                <br>

                <input type="submit" value="Update">
            </form>
        </div>
    </center>

</body>

</html>
