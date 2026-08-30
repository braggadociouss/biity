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

$usersdb->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="stylesheet"
        type="text/css"
        media="screen"
        href="assets/css/main.css"
    >

    <link
        rel="stylesheet"
        type="text/css"
        media="screen"
        href="assets/css/post.css"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap"
        rel="stylesheet"
    >

    <script src="assets/js/main.js" defer></script>
    <script src="assets/js/post.js" defer></script>
    <script src="assets/js/navbar.js" defer></script>

    <title>Post</title>
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
            if (isset($_SESSION["username"]) && isset($_SESSION["user_id"])) {
                echo "<span class='welcome-msg'>Hello, " .
                    htmlspecialchars($_SESSION["username"]) .
                    "!</span>";

                if ($profile_picture) {
                    echo "<div class='dropdown'>";

                    echo "<img
                        class='profile-pic'
                        src='" . htmlspecialchars($profile_picture) . "'
                        alt='Your profile picture'
                        onclick='toggleDropdown()
                    '>";

                    echo "<div class='dropdown-content' id='dropdown-menu'>";
                    echo "<a href='/newpost.php'>New Post</a>";
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

    <div class="post-container">

        <?php
        require("./ndatabase.php");

        $userId = isset($_SESSION["user_id"])
            ? $_SESSION["user_id"]
            : 0;

        if (isset($_GET["post_id"]) && is_numeric($_GET["post_id"])) {

            $postId = intval($_GET["post_id"]);

            $sql = "SELECT * FROM posts WHERE post_id = ?";

            $stmt = $usersdb->prepare($sql);
            $stmt->bind_param("i", $postId);
            $stmt->execute();

            $result1 = $stmt->get_result();

            if ($result1->num_rows > 0) {

                $post = $result1->fetch_assoc();

                $sql = "
                    SELECT profile_picture, username
                    FROM users
                    WHERE user_id = ?
                ";

                $stmt = $usersdb->prepare($sql);
                $stmt->bind_param("i", $post["creator_id"]);
                $stmt->execute();

                $result2 = $stmt->get_result();
                $userdata = $result2->fetch_assoc();

                echo '<div><div>';
                echo '<img
                    class="user-profile-pic"
                    src="' . htmlspecialchars(
                        $userdata["profile_picture"] ?? ""
                    ) . '"
                    alt="Profile Picture"
                > ';

                echo '<b style="color: black;">' .
                    htmlspecialchars(
                        $userdata["username"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    ) .
                    '</b></div>';

                echo '<p>' .
                    htmlspecialchars(
                        $post["description"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) .
                    '</p>';

                if (isset($post["image_link"])) {
                    echo '<center>
                        <img
                            class="post-image"
                            src="' . htmlspecialchars(
                                $post["image_link"]
                            ) . '"
                            alt="Post Image"
                        >';
                }

                if (isset($post["video_link"])) {
                    echo '<video
                        playsinline
                        class="post-image"
                        alt="Post Video"
                        controls
                    >
                        <source src="' .
                        htmlspecialchars($post["video_link"]) .
                        '#t=0.001">
                    </video>';
                }

                echo '</div>';

                if (
                    $_SERVER["REQUEST_METHOD"] === "POST" &&
                    isset($_POST["vote"]) &&
                    isset($_SESSION["user_id"])
                ) {
                    $voteType = $_POST["vote"];
                    $user_id = $_SESSION["user_id"];

                    if (
                        $voteType !== "like" &&
                        $voteType !== "dislike"
                    ) {
                        exit();
                    }

                    $check = $usersdb->prepare(
                        "SELECT vote
                        FROM post_votes
                        WHERE user_id = ? AND post_id = ?"
                    );

                    $check->bind_param(
                        "ii",
                        $user_id,
                        $postId
                    );

                    $check->execute();

                    $result = $check->get_result();

                    if ($result->num_rows > 0) {

                        $existing = $result->fetch_assoc()["vote"];

                        if ($existing !== $voteType) {

                            $update = $usersdb->prepare(
                                "UPDATE post_votes
                                SET vote = ?
                                WHERE user_id = ?
                                AND post_id = ?"
                            );

                            $update->bind_param(
                                "sii",
                                $voteType,
                                $user_id,
                                $postId
                            );

                            $update->execute();
                            $update->close();
                        }

                    } else {

                        $insert = $usersdb->prepare(
                            "INSERT INTO post_votes
                            (user_id, post_id, vote)
                            VALUES (?, ?, ?)"
                        );

                        $insert->bind_param(
                            "iis",
                            $user_id,
                            $postId,
                            $voteType
                        );

                        $insert->execute();
                        $insert->close();
                    }

                    header(
                        "Location: post.php?post_id=$postId"
                    );

                    exit();
                }

                $comments_query = $usersdb->prepare(
                    "SELECT c.comment_text, c.created_at,
                            u.username, u.profile_picture
                    FROM comments c
                    JOIN users u
                        ON c.user_id = u.user_id
                    WHERE c.post_id = ?
                    ORDER BY c.created_at DESC
                    LIMIT 30"
                );

                $comments_query->bind_param("i", $postId);
                $comments_query->execute();

                $comments_result = $comments_query->get_result();

                echo '<div class="post-actions">';

                $like_count = 0;
                $dislike_count = 0;

                $q = $usersdb->prepare(
                    "SELECT COUNT(*)
                    FROM post_votes
                    WHERE post_id = ?
                    AND vote = 'like'"
                );

                $q->bind_param("i", $postId);
                $q->execute();
                $q->bind_result($like_count);
                $q->fetch();
                $q->close();

                $q = $usersdb->prepare(
                    "SELECT COUNT(*)
                    FROM post_votes
                    WHERE post_id = ?
                    AND vote = 'dislike'"
                );

                $q->bind_param("i", $postId);
                $q->execute();
                $q->bind_result($dislike_count);
                $q->fetch();
                $q->close();

                if (isset($_SESSION["user_id"])) {
                    echo '
                        <form method="post" style="display: inline;">
                            <button
                                type="submit"
                                name="vote"
                                value="like"
                            >
                                👍 Like (' .
                                intval($like_count) .
                                ')
                            </button>
                        </form>

                        <form method="post" style="display: inline;">
                            <button
                                type="submit"
                                name="vote"
                                value="dislike"
                            >
                                👎 Dislike (' .
                                intval($dislike_count) .
                                ')
                            </button>
                        </form>
                    ';
                } else {
                    echo "
                        <p>
                            You're not logged in, so you can't interact
                            with this post!
                        </p>
                    ";
                }

                echo '<div class="comments-section">';
                echo '<h3>Comments</h3>';

                if (isset($_SESSION["username"])) {
                    echo '
                        <form action="" method="post">
                            <textarea
                                name="comment_text"
                                required
                            ></textarea>

                            <button type="submit">
                                Post Comment
                            </button>
                        </form>
                    ';
                }

                while ($comment = $comments_result->fetch_assoc()) {
                    echo '<div class="comment">';

                    echo '<img
                        src="' .
                        htmlspecialchars(
                            $comment["profile_picture"]
                        ) .
                        '"
                        class="comment-profile-pic"
                        alt="Profile Picture"
                    >';

                    echo '<div class="comment-content">';

                    echo '<p>
                        <strong>' .
                        htmlspecialchars(
                            $comment["username"]
                        ) .
                        ':</strong> ' .
                        htmlspecialchars(
                            $comment["comment_text"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) .
                        '
                    </p>';

                    echo '<small>' .
                        htmlspecialchars(
                            $comment["created_at"]
                        ) .
                        '</small>';

                    echo '</div>';
                    echo '</div>';
                }

                echo '</div>';

                if (
                    $_SERVER["REQUEST_METHOD"] == "POST" &&
                    isset($_POST["comment_text"]) &&
                    isset($_SESSION["username"])
                ) {
                    $comment_text = htmlspecialchars(
                        $_POST["comment_text"]
                    );

                    $user_query = $usersdb->prepare(
                        "SELECT user_id
                        FROM users
                        WHERE username = ?"
                    );

                    $user_query->bind_param(
                        "s",
                        $_SESSION["username"]
                    );

                    $user_query->execute();
                    $user_query->bind_result($user_id);
                    $user_query->fetch();
                    $user_query->close();

                    $comment_insert = $usersdb->prepare(
                        "INSERT INTO comments
                        (post_id, user_id, comment_text)
                        VALUES (?, ?, ?)"
                    );

                    $comment_insert->bind_param(
                        "iis",
                        $postId,
                        $user_id,
                        $comment_text
                    );

                    $comment_insert->execute();
                    $comment_insert->close();

                    header(
                        "Location: post.php?post_id=$postId"
                    );

                    exit();
                }

            } else {
                echo "<p>Post not found.</p>";
            }

            $stmt->close();

        } else {
            echo "<p>Invalid or missing post ID.</p>";
        }

        $usersdb->close();
        ?>

    </div>

</body>

</html>