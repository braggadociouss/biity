<?php

session_start();

require("./database.php");

$user_id = $_GET["id"];

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

$stmt = $usersdb->prepare(
    "SELECT profile_picture, username, bio FROM users WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$userinfo = $stmt->get_result();

$stmt = $usersdb->prepare(
    "SELECT * FROM posts WHERE creator_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$posts = $stmt->get_result();

$stmt = $usersdb->prepare(
    "SELECT * FROM comments WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$comments = $stmt->get_result();

if ($userinfo->num_rows > 0) {
    $row = $userinfo->fetch_assoc();

    $their_profile_picture = $row["profile_picture"];
    $their_bio = $row["bio"];
    $their_username = $row["username"];
}

$stmt = $usersdb->prepare(
    "SELECT COUNT(*) AS likes_received
     FROM post_votes
     JOIN posts ON post_votes.post_id = posts.post_id
     WHERE posts.creator_id = ?
     AND post_votes.vote = 'like'"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$row2 = $stmt->get_result();
$likes = $row2->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/usr.css">

    <script src="/assets/js/main.js" defer></script>

    <title><?= htmlspecialchars($their_username) ?>'s profile</title>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-logo" href="/index.php">
            <img alt="biity" src="/assets/img/biity.png">
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
                            <?php if ($is_admin): ?>
                                <a href="/admin">Admin Panel</a>
                            <?php endif; ?>

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

    <div class="superparent">
        <center>
            <div class="criticalinfo">
                <img
                    class="bigimg"
                    src="<?= htmlspecialchars($their_profile_picture) ?>"
                    alt="Profile Picture"
                >

                <h1><?= htmlspecialchars($their_username) ?></h1>

                <div class="important-stacked">
                    <div class="likes">
                        <h1 style="margin: 0px;">
                            <?= $likes["likes_received"] ?>
                        </h1>

                        <h1 style="margin: 0px;">
                            likes
                        </h1>
                    </div>

                    <div class="comments">
                        <h1 style="margin: 0px;">
                            <?= $comments->num_rows ?>
                        </h1>

                        <h1 style="margin: 0px;">
                            comments
                        </h1>
                    </div>

                    <div class="comments">
                        <h1 style="margin: 0px;">
                            <?= $posts->num_rows ?>
                        </h1>

                        <h1 style="margin: 0px;">
                            posts
                        </h1>
                    </div>
                </div>

                <h1>
                    <?= htmlspecialchars($their_bio ?? "No bio.", ENT_QUOTES, "UTF-8") ?>
                </h1>
            </div>
        </center>

        <div class="sections">
            <div class="posts usrsection">
                <h1>Posts</h1>

                <?php if ($posts->num_rows == 0): ?>
                    <h1>This user hasn't posted anything.</h1>
                <?php endif; ?>

                <?php while ($row = $posts->fetch_assoc()): ?>
                    <div class="post">
                        <div class="user-info">
                            <img
                                src="<?= htmlspecialchars($their_profile_picture) ?>"
                                alt="Profile Picture"
                            >

                            <span>
                                <?= htmlspecialchars($their_username) ?>
                            </span>
                        </div>

                        <div class="post-content">
                            <p>
                                <?= htmlspecialchars($row["description"], ENT_QUOTES, "UTF-8") ?>
                            </p>

                            <?php if (!empty($row["image_link"])): ?>
                                <img
                                    class="post-image"
                                    src="<?= htmlspecialchars($row["image_link"]) ?>"
                                    alt="Post Image"
                                >
                            <?php endif; ?>

                            <?php if (!empty($row["video_link"])): ?>
                                <video
                                    playsinline
                                    class="post-image"
                                    controls
                                >
                                    <source
                                        src="<?= htmlspecialchars($row["video_link"]) ?>#t=0.001"
                                    >
                                </video>
                            <?php endif; ?>

                            <div class="post-stats">
                                <a href="/post.php?post_id=<?= $row["post_id"] ?>">
                                    See more
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="comments usrsection">
                <h1>Comments</h1>

                <?php if ($comments->num_rows == 0): ?>
                    <h1>This user hasn't posted any comments.</h1>
                <?php endif; ?>

                <?php while ($row = $comments->fetch_assoc()): ?>
                    <div class="comment">
                        <img
                            src="<?= htmlspecialchars($their_profile_picture) ?>"
                            class="comment-profile-pic"
                            alt="Profile Picture"
                        >

                        <div class="comment-content">
                            <b>
                                <?= htmlspecialchars($their_username) ?>
                            </b>

                            <br>

                            <strong>
                                <?= htmlspecialchars($row["username"]) ?>
                            </strong>

                            <?= htmlspecialchars($row["comment_text"]) ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>

</html>
