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
            
            $stmt = $conn->prepare('SELECT tag_id FROM post_tags WHERE post_id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $tags = [];
            while ($row = $result->fetch_assoc()) {
                $tags[] = $row['tag_id'];
            }

            //update post
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['title'] && $_POST['content']) {
                    $updTitle = htmlspecialchars($_POST['title']);
                    $updContent = htmlspecialchars($_POST['content']);
                    $stmt = $conn->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ? AND author_id = ?');
                    $stmt->bind_param('ssii', $updTitle, $updContent, $id, $userId);
                        
                    if ($stmt->execute()) {
                        $conn->query("DELETE FROM post_tags WHERE post_id = $id");

                        if (!empty($_POST['tags'])) {
                            $stmt = $conn->prepare('INSERT INTO post_tags VALUES (?,?)');
                            foreach ($_POST['tags'] as $tagId) {
                                $tagId = (int)$tagId;
                                    
                                $stmt->bind_param('ii', $id, $tagId);
                                $stmt->execute();
                            }
                        }
                        $msg = 'Post updated successfully';
                    } 
                    else $msg = 'Something went wrong';
                }
                
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
            <label><input type="checkbox" name="tags[]" value="1" <?php if (in_array(1, $tags)) echo "checked"; ?>> Cricket</label>
            <label><input type="checkbox" name="tags[]" value="2" <?php if (in_array(2, $tags)) echo "checked"; ?>>Football</label>
            <label><input type="checkbox" name="tags[]" value="3" <?php if (in_array(3, $tags)) echo "checked"; ?>>Tennis</label>
            <label><input type="checkbox" name="tags[]" value="4" <?php if (in_array(4, $tags)) echo "checked"; ?>>F1</label>
            <label><input type="checkbox" name="tags[]" value="5" <?php if (in_array(5, $tags)) echo "checked"; ?>>Others</label>
            
            <button type="submit">UPDATE</button> | <a href="dashboard.php">CANCEL</a>
        </form>
    </body>
</html>