<?php
session_start();

$msg = '';
if (isset($_SESSION['username'])){
    $userId = $_SESSION['userId'];
    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['title'] && $_POST['content']) {
            require_once '../inc/db.php';

            $title = htmlspecialchars($_POST['title'], ENT_NOQUOTES);
            $content = $_POST['content'];
            $time = date('Y-m-d H:i:s');
            $userId = $_SESSION['userId'];

            $stmt = $conn->prepare('INSERT INTO posts (title, content, created_at, author_id) VALUES(?,?,?,?)');
            $stmt->bind_param('sssi', $title, $content, $time, $userId);

            if ($stmt->execute()){
                if (!empty($_POST['tags'])) {
                    $result = $conn->query("SELECT MAX(id) AS max_id FROM posts");
                    $postId = $result->fetch_assoc()['max_id'];
                    
                    $stmt = $conn->prepare('INSERT INTO post_tags VALUES (?,?)');
                    foreach ($_POST['tags'] as $tagId) {
                        $tagId = (int)$tagId;
                        $stmt->bind_param('ii', $postId, $tagId);
                        $stmt->execute();
                    }
                }
                $msg = 'Posted successfully!';
            }
        }
    }
} else $msg = 'Invalid session';

?>

<html>
    <body>
        <?php if ($msg): ?>
            <?= $msg ?>
            <meta http-equiv="refresh" content="3;url=dashboard.php">
        <?php endif; ?>

        <h3>New Post</h3><br><br>
        <form method="POST">
            <input type="text" name="title" placeholder="Title" required><br><br>
            <textarea name="content" rows="10" cols="50" placeholder="Write a bit..." required></textarea><br><br>
            <label><input type="checkbox" name="tags[]" value="1">Cricket</label>
            <label><input type="checkbox" name="tags[]" value="2">Football</label>
            <label><input type="checkbox" name="tags[]" value="3">Tennis</label>
            <label><input type="checkbox" name="tags[]" value="4">F1</label>
            <label><input type="checkbox" name="tags[]" value="5">Others</label>
            
            <button type="submit">POST</button>
        </form>
    </body>
</html>