
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Timeline</title>

    <link rel="stylesheet" href="/assets/css/tline.css">
    <link rel="stylesheet" href="/assets/css/main.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap"
        rel="stylesheet"
    >

    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/navbar.js" defer></script>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-logo" href="/index.php">
            <img
                src="/assets/img/biity.png"
                alt="biity"
            >
        </a>

        <div class="navbar-links">
            <a href="/timeline.php" class="navbtn">Timeline</a>
            <a href="/users.php" class="navbtn">Users</a>
        </div>

        <div class="navbar-profile">
            <?php if (isset($_SESSION["username"]) && isset($_SESSION["user_id"])): ?>

                <span class="welcome-msg">
                    Hello, <?= htmlspecialchars($_SESSION["username"]) ?>!
                </span>

                <?php if ($profile_picture): ?>

                    <div class="dropdown">
                        <img
                            class="profile-pic"
                            src="<?= htmlspecialchars($profile_picture) ?>"
                            alt="Your profile picture"
                            onclick="toggleDropdown()"
                        >

                        <div class="dropdown-content" id="dropdown-menu">
                            <a href="/newpost.php">New Post</a>
                            <a href="/api/logout.php">Log Out</a>
                            <a href="/settings.php">Settings</a>
                        </div>
                    </div>

                <?php endif; ?>

            <?php else: ?>

                <a href="/login.php" class="navbtn">Sign In</a>

            <?php endif; ?>
        </div>
    </nav>

    <h1>Timeline</h1>

    <div id="posts-ajaxwrapper">
        <!-- Posts will render here automatically every 30 seconds -->
    </div>

    <script>
        function loadPosts() {
            fetch("render.php")
                .then(res => res.text())
                .then(html => {
                    document.getElementById("posts-ajaxwrapper").innerHTML = html;
                });
        }

        loadPosts();
        setInterval(loadPosts, 30000);
    </script>
</body>

</html>
