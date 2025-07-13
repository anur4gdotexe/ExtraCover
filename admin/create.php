<?php
session_start();

$msg = '';
if (isset($_SESSION['username'])){
    $userId = $_SESSION['userId'];
    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['title'] && $_POST['content']) {
            require_once '../inc/db.php';

            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);
            $time = date('Y-m-d H:i:s');
            $userId = $_SESSION['userId'];

            $stmt = $conn->prepare('INSERT INTO posts (title, content, created_at, author_id) VALUES(?,?,?,?)');
            $stmt->bind_param('sssi', $title, $content, $time, $userId);

            if ($stmt->execute()){
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
            <textarea name="content" rows="10" cols="50" required>Write a bit...</textarea><br><br>
            
            <button type="submit">POST</button>
        </form>
    </body>
</html>