<?php

require_once 'inc/db.php';
require_once 'inc/functions.php';

$msg = '';
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'] ?? 0;

    $stmt = $conn->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    if (!$post) $msg = 'No post found';
    else {
        $title = htmlspecialchars($post['title'], ENT_NOQUOTES);
        $content = nl2br(htmlspecialchars($post['content'], ENT_NOQUOTES));

        $time = getTime($post['created_at']);

        $authorId = (int)$post['author_id'];
        $author = $conn->query("SELECT username FROM users WHERE id = $authorId");
        $authorName = $author->fetch_assoc()['username'];
    }

} else $msg = 'No post found';

?>

<html>
    <head>
        <link rel="stylesheet" href="styles/post.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        </style>
    </head>
    <body>
        <?php if ($msg): ?>
            <?= $msg ?>
            <meta http-equiv="refresh" content="3;url=admin/dashboard.php">
        <?php endif; ?>
        
        <main class="article-container">
            <div class="article-header">
                <div class="article-title"><?= $title ?></div>
                <div class="article-meta">
                    <span class="author"><?= $authorName ?></span>
                    <span class="dot">&bull;</span>
                    <span class="time"><?= $time ?></span>
                </div>
            </div>
            
            <div class="article-content"><?= $content ?></div>
        </main>
    </body>
</html>