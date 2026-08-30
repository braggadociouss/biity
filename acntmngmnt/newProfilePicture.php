<?php
session_start();
require("../database.php");


$profile_picture = null;

if (isset($_SESSION["username"])) {
    $stmt = $usersdb->prepare("SELECT profile_picture FROM users WHERE username = ?");
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
    exit(); 
}
$usersdb->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="/assets/js/autosubmit.js"></script>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/main.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/pfp.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/css/login.css'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap" rel="stylesheet">
    <script src='/assets/js/main.js' defer></script>
    <title>Change your profile picture</title>
</head>
<body>
    <div class="container">
        <h2>Change your profile picture</h2>
        <?php if ($profile_picture): ?>
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="bigimg">
        <?php endif; ?>
        <form action="/api/pfp.php" method="post" enctype="multipart/form-data">
            <div>
              <button class="buttonlabel">  <label class="buttonlabel" for="new_profile_picture">Upload profile picture</label></button>
                <input class="buttonlabel" type="file" name="new_profile_picture" id="new_profile_picture" accept="image/*" required>
            </div>
        </form>
    </div>
</body>
</html>
