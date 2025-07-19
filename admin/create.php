<?php
session_start();
require_once '../inc/db.php';

$msg = '';
if (isset($_SESSION['username'])){
    $userId = $_SESSION['userId'];
    $username = $_SESSION['username'];

    $author = $conn->query("SELECT username FROM users WHERE id = $userId");
    $authorName = $author->fetch_assoc()['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['title'] && $_POST['content']) {
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
    <head>
        <link rel="stylesheet" href="../styles/create.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        </style>
    </head>
    <body>
        <?php if ($msg): ?>
            <?= $msg ?>
            <meta http-equiv="refresh" content="3;url=dashboard.php">
        <?php endif; ?>
        
        <main class="article-form-container">
            <form class="article-form" method="POST">

                <div class="form-heading">
                    <div class="new-article">New Article</div>
                    <div class="by">&bull; by <span class="author"><?= $authorName ?></span></div>
                </div>

                <div class="form-input">
                    <div class="form-content">
                        <input type="text" id ="title" name="title" placeholder="Title" required/>
                        <hr>
                        <textarea id="content" name="content" rows="10" placeholder="Write a bit..." required></textarea>
                    </div>
                </div>

                <div class="category-input">
                    <legend>Select Categories</legend>
                    <fieldset class="categories">
                        <input type="checkbox" name="tags[]" value="1"><label>Cricket</label>
                        <input type="checkbox" name="tags[]" value="2"><label>Football</label>
                        <input type="checkbox" name="tags[]" value="3"><label>Tennis</label>
                        <input type="checkbox" name="tags[]" value="4"><label>F1</label>
                        <input type="checkbox" name="tags[]" value="5"><label>Others</label>
                    </fieldset>
                </div>

                <div class="button"><button type="submit">Post Article</button></div>
            </form>
        </main>
    </body>
</html>