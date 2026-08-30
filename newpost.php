```php
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

<?php

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: /login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $description = $_POST["description"];
    $image = $_FILES["image"] ?? null;
    $username = $_SESSION["username"];

    $stmt = $usersdb->prepare(
        "SELECT user_id FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($userId);
    $stmt->fetch();
    $stmt->close();

    if (!$userId) {
        customError("User not found.");
        goHome();
        exit;
    }

    $targetFile = null;

    if ($image && $image["error"] !== UPLOAD_ERR_NO_FILE) {

        if (str_starts_with($fileType, "image/")) {

            if ($image["error"] !== UPLOAD_ERR_OK) {
                customError(
                    "Your attachment didn't upload, so your post has been canceled. Try again! Error" .
                    $image["error"]
                );

                goHome();
                exit;
            }

            $targetDir = __DIR__ . "/assets/user-uploads/img/";

            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0755, true)) {
                    customError(
                        "Your attachment didn't upload, so your post has been canceled."
                    );

                    goHome();
                    exit;
                }
            }

            if (!is_writable($targetDir)) {
                customError(
                    "Your attachment didn't upload, so your post has been canceled."
                );

                goHome();
                exit;
            }

            $allowedTypes = [
                "image/jpeg",
                "image/jpg",
                "image/png",
                "image/gif",
                "image/webp"
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $image["tmp_name"]);
            finfo_close($finfo);

            if (!in_array($fileType, $allowedTypes)) {
                customError("This file can't be uploaded.");
                goHome();
                exit;
            }

            if ($image["size"] > 100 * 1024 * 1024) {
                customError("File too large! 10MB maximum.");
                goHome();
                exit;
            }

            $ext = pathinfo(
                $image["name"],
                PATHINFO_EXTENSION
            );

            $filename = bin2hex(random_bytes(8)) . "." . $ext;
            $finalPath = $targetDir . $filename;

            if (!move_uploaded_file(
                $image["tmp_name"],
                $finalPath
            )) {
                customError(
                    "Your attachment didn't upload, so your post has been canceled."
                );

                goHome();
                exit;
            }

            $targetFile = "/assets/user-uploads/img/" . $filename;

        } else {

            if ($image["error"] !== UPLOAD_ERR_OK) {
                customError(
                    "Your attachment didn't upload, so your post has been canceled. Try again! Error" .
                    $image["error"]
                );

                goHome();
                exit;
            }

            $targetDir = __DIR__ . "/assets/user-uploads/video/";

            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0755, true)) {
                    customError(
                        "Your attachment didn't upload, so your post has been canceled."
                    );

                    goHome();
                    exit;
                }
            }

            if (!is_writable($targetDir)) {
                customError(
                    "Your attachment didn't upload, so your post has been canceled."
                );

                goHome();
                exit;
            }

            $allowedTypes = [
                "video/mp4",
                "video/webm",
                "video/ogg",
                "video/quicktime",
                "video/x-msvideo",
                "video/x-matroska"
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $image["tmp_name"]);
            finfo_close($finfo);

            if (!in_array($fileType, $allowedTypes)) {
                customError("This file can't be uploaded.");
                goHome();
                exit;
            }

            if ($image["size"] > 100 * 1024 * 1024) {
                customError("File too large. Max 10MB.");
                goHome();
                exit;
            }

            $ext = pathinfo(
                $image["name"],
                PATHINFO_EXTENSION
            );

            $filename = bin2hex(random_bytes(8)) . "." . $ext;
            $finalPath = $targetDir . $filename;

            if (!move_uploaded_file(
                $image["tmp_name"],
                $finalPath
            )) {
                customError(
                    "Your attachment didn't upload, so your post has been canceled."
                );

                goHome();
                exit;
            }

            $video = "/assets/user-uploads/video/" . $filename;
        }
    }

    $stmt = $usersdb->prepare(
        "
        INSERT INTO posts (
            creator_id,
            description,
            image_link,
            video_link
        )
        VALUES (?, ?, ?, ?)
        "
    );

    $stmt->bind_param(
        "isss",
        $userId,
        $description,
        $targetFile,
        $video
    );

    if ($stmt->execute()) {
        header("Location: timeline.php");
        exit();
    } else {
        customError(
            "Warning, an unexpected error has occured" .
            $stmt->error
        );

        goHome();
        exit;
    }

    $stmt->close();
    $usersdb->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link
        rel="stylesheet"
        type="text/css"
        href="assets/css/login.css"
    >

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

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
        href="assets/css/newpost.css"
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

    <script
        src="/assets/js/main.js"
        defer
    ></script>

    <script
        src="/assets/js/newpost.js"
        defer
    ></script>

    <title>New Post</title>
</head>

<body>

    <center>

        <nav class="navbar">

            <a class="navbar-logo" href="/index.php">
                <img
                    alt="biity"
                    src="/assets/img/biity.png"
                >
            </a>

            <div class="navbar-links">
                <a
                    href="/timeline.php"
                    class="navbtn"
                >
                    Timeline
                </a>

                <a
                    href="/users.php"
                    class="navbtn"
                >
                    Users
                </a>
            </div>

            <div class="navbar-profile">

                <?php
                if (
                    isset($_SESSION["username"]) &&
                    isset($_SESSION["user_id"])
                ):
                ?>

                    <span class="welcome-msg">
                        Hello,
                        <?= htmlspecialchars($_SESSION["username"]) ?>!
                    </span>

                    <?php if ($profile_picture): ?>

                        <div class="dropdown">

                            <img
                                class="profile-pic"
                                src="<?= $profile_picture ?>"
                                alt="Your profile picture"
                                onclick="toggleDropdown()"
                            >

                            <div
                                class="dropdown-content"
                                id="dropdown-menu"
                            >

                                <a href="/newpost.php">
                                    New Post
                                </a>

                                <?php if ($is_admin): ?>

                                    <a href="/admin">
                                        Admin Panel
                                    </a>

                                <?php endif; ?>

                                <a href="/api/logout.php">
                                    Log Out
                                </a>

                                <a href="/settings.php">
                                    Settings
                                </a>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php else: ?>

                    <a
                        href="/login.php"
                        class="navbtn"
                    >
                        Sign In
                    </a>

                <?php endif; ?>

            </div>

        </nav>

        <br>

        <div class="container">

            <h2>Create a New Post</h2>

            <form
                action="newpost.php"
                method="post"
                enctype="multipart/form-data"
            >

                <img
                    id="imgPreview"
                    src="/assets/img/videophoto.png"
                >

                <video
                    id="video"
                    class="video"
                    controls
                >
                    <source src="">
                </video>

                <div class="form-group">
                    <input
                        type="text"
                        name="description"
                        id="description"
                        required
                    >
                </div>

                <div class="form-group">
                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept="image/*, video/*"
                    >
                </div>

                <center>

                    <div class="form-group">
                        <button
                            type="submit"
                            name="submit"
                        >
                            Post
                        </button>
                    </div>

                </center>

            </form>

        </div>

    </center>

</body>

</html>
```
