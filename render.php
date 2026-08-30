<?php 
    require("database.php");
    $query = "
        SELECT posts.*, users.username, users.profile_picture 
        FROM posts 
        JOIN users ON posts.creator_id = users.user_id 
        ORDER BY posts.post_id DESC
        LIMIT 50
    ";
    $result = $usersdb->query($query);
?>

<?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class='post'>
                <div class='user-info'>
                    <img src='<?= htmlspecialchars($row['profile_picture']) ?>' alt='Profile Picture'>
                    <span><?= htmlspecialchars($row['username']) ?></span>
                </div>
                <div class='post-content'>
                    <p><?= htmlspecialchars($row['description'], ENT_QUOTES) ?></p>
                    <?php if (!empty($row['image_link'])): ?>
                        <img class="post-image" src='<?= htmlspecialchars($row['image_link']) ?>' alt='Post Image'>
                    <?php endif; ?>
                    <?php if (!empty($row['video_link'])): ?>
                        <video playsinline class="post-image" controls> 
                        <source src='<?= htmlspecialchars($row['video_link'])?>#t=0.001' alt='Post Video'>
                    </video>
                    <?php endif; ?>
                    <div class='post-stats'>
                        <a href='/post.php?post_id=<?= $row["post_id"] ?>'>See more</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
<?php endif; ?>
