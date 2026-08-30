<?php

session_start();

require("./database.php");

$profile_picture = null;
$is_admin = false;

if (isset($_SESSION["username"])) {
    $stmt = $usersdb->prepare(
        "SELECT profile_picture, admin FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $profile_picture = $row["profile_picture"];
        $is_admin = $row["admin"] == 1;
    }

    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" media="screen" href="./assets/css/main.css">
    <link rel="stylesheet" type="text/css" media="screen" href="./assets/css/users.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap"
        rel="stylesheet"
    >

    <script src="assets/js/navbar.js" defer></script>
    <script src="/assets/js/main.js"></script>
    <title>Users</title>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-logo" href="/index.php">
            <img src="/assets/img/biity.png" alt="biity">
        </a>

        <div class="navbar-links">
            <a href="/timeline.php" class="navbtn">Timeline</a>
            <a href="/users.php" class="navbtn">Users</a>
        </div>

        <div class="navbar-profile">
            <?php
            if (isset($_SESSION["username"]) && isset($_SESSION["user_id"])) {
                echo "<span class='welcome-msg'>Hello, " .
                    htmlspecialchars($_SESSION["username"]) .
                    "!</span>";

                if ($profile_picture) {
                    echo "<div class='dropdown'>";

                    echo "<img
                        class='profile-pic'
                        src='" . $profile_picture . "'
                        alt='Your profile picture'
                        onclick='toggleDropdown()'
                    >";

                    echo "<div class='dropdown-content' id='dropdown-menu'>";

                    echo "<a href='/newpost.php'>New Post</a>";

                    if ($is_admin) {
                        echo "<a href='/admin'>Admin Panel</a>";
                    }

                    echo "<a href='/api/logout.php'>Log Out</a>";
                    echo "<a href='/settings.php'>Settings</a>";

                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<a href='/login.php' class='navbtn'>Sign In</a>";
            }
            ?>
        </div>
    </nav>

    <!-- Page system not in use, so this could take forever if the number of users is limited.
         I'll just leave it like this for simplicity regardless. -->

    <div class="users">
        <?php
        // List all users (for now)
        $query = "
            SELECT users.*
            FROM users
            ORDER BY users.user_id
        ";

        $result = $usersdb->query($query);

        if ($result === false) {
            die("Query failed: " . $usersdb->error);
        }
        ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

                <div
                    class="user"
                    onclick='window.location.href="/user.php?id=<?= $row["user_id"] ?>"'
                >
                    <center>
                        <h1><?= htmlspecialchars($row["username"]) ?></h1>

                        <img
                            src="<?= htmlspecialchars($row["profile_picture"]) ?>"
                            alt="Profile Picture"
                        >

                        <p>
                            <?= html_entity_decode(
                                htmlspecialchars(
                                    $row["bio"] ?? "No bio.",
                                    ENT_QUOTES,
                                    "UTF-8"
                                ),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </p>
                    </center>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>

