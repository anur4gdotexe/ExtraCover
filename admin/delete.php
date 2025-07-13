<?php
session_start();

$msg = '';
if (isset($_SESSION['username'])){
    if (isset($_GET['id'])) {
        include '../inc/db.php';

        $id = $_GET['id'];
        $userId = $_SESSION['userId'];

        $stmt = $conn->prepare('SELECT * FROM posts WHERE id = ? AND author_id = ?');
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $post = $result->fetch_assoc();
        if (!$post) {
            $msg = 'No post found';
        }
        else {
            $stmt = $conn->prepare('DELETE FROM posts WHERE id = ? AND author_id = ?');
            $stmt->bind_param('ii', $id, $userId);
                        
            if ($stmt->execute()) $msg = 'Post deleted';
            else $msg = 'Something went wrong';
        }  
    } else $msg = 'No such post found';
} else $msg = 'Invalid session';
?>

<html>
    <body>
        <?php if ($msg): ?>
            <?= $msg ?>
            <meta http-equiv="refresh" content="3;url=dashboard.php">
        <?php endif; ?>
    </body>
</html>