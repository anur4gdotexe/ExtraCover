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
            $title = $post['title'];
            $content = $post['content'];

            //update post
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['title'] && $_POST['content']) {
                    if ($_POST['title'] == $title && $_POST['content'] == $content) $msg = 'No changes made';
                    else {
                        $updTitle = htmlspecialchars($_POST['title']);
                        $updContent = htmlspecialchars($_POST['content']);

                        $stmt = $conn->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ? AND author_id = ?');
                        $stmt->bind_param('ssii', $updTitle, $updContent, $id, $userId);
                        
                        if ($stmt->execute()) $msg = 'Post updated successfully';
                        else $msg = 'Something went wrong';
                    }
                }
                else $msg = 'Post can\'t be empty';
            }
        }  
    }
    else $msg = 'No such post found';
} else $msg = 'Invalid session';
?>

<html>
    <body>
        <?php if ($msg): ?>
            <?= $msg ?>
            <meta http-equiv="refresh" content="3;url=dashboard.php">
        <?php endif; ?>

        <h3>Edit Post</h3><br><br>
        <form method="POST">
            <input type="text" name="title" placeholder="Title" value="<?= $title ?>" required><br><br>
            <textarea name="content" rows="10" cols="50" required><?= $content ?></textarea><br><br>
            
            <button type="submit">UPDATE</button> | <a href="dashboard.php">CANCEL</a>
        </form>
    </body>
</html>