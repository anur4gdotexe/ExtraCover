<?php
require_once 'inc/db.php';
require_once 'inc/functions.php';

if (isset($_GET['id'])) {
    $tagId = (int)$_GET['id'];

    $stmt = $conn->prepare('SELECT posts.* FROM posts JOIN post_tags ON posts.id = post_tags.post_id WHERE post_tags.tag_id = ? ORDER BY created_at DESC');
    $stmt->bind_param('i', $tagId);

    $stmt->execute();
    $result = $stmt->get_result();
}
else {
    $result = $conn->query('SELECT * FROM posts ORDER BY created_at DESC');
}
?>
<html>
    <head>
        <link rel="stylesheet" href="styles/index.css">
        <script src="inc/functions.js"></script>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        </style>
    </head>
    <body>
        <header class="header">
            <div class="app-name">ExtraCover</div>                
            <a href="admin/login.php" class="login-button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>    
                LOGIN
            </a>
        </header>

        <div class="container" style="display:flex; align-items: flex-start">
            <main class="post-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                <?php 
                    $full = htmlspecialchars($row['content'], ENT_NOQUOTES);
                    $preview = substr($full, 0, 500);
                    $trimtill = strrpos($preview, ' ');
                    $preview = substr($preview, 0, $trimtill);

                    $authorId = (int)$row['author_id'];
                    $author = $conn->query("SELECT username FROM users WHERE id = $authorId");
                    $authorName = $author->fetch_assoc()['username'];
                ?>

                <article class="post">
                    <div class="post-header">
                        <div class="post-title"><a href="post.php?id=<?= $row['id'] ?>" class="post-link"><?= $row['title'] ?></a></div>
                        <span class="post-time"><?= getTime($row['created_at']) ?></span>
                    </div>
                    <div class="post-author"><?= $authorName ?></div>

                    <div class="post-content">
                        <p class="preview" id="preview-<?= $row['id'] ?>"><?= $preview ?>
                        <a class="toggle-link" href="javascript:void(0);" onclick="toggleContent(<?= $row['id'] ?>)" id="toggle-<?=$row['id']?>">...See more</a></p>
                        <p class="full-content" id="full-<?= $row['id'] ?>" style="display:none"><?= nl2br($full) ?>
                        <a class="toggle-link" href="javascript:void(0);" onclick="toggleContent(<?= $row['id'] ?>)" id="toggle-<?=$row['id']?>(1)">See less</a></p>
                    </div>
                </article>
                <?php endwhile; ?>
                </main>

            <nav class="tags">
                <span class="tag-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    Categories
                </span>
                <a href="index.php?id=1" class="tag-block">Cricket</a>
                <a href="index.php?id=2" class="tag-block">Football</a>
                <a href="index.php?id=3" class="tag-block">Tennis</a>
                <a href="index.php?id=4" class="tag-block">F1</a>
                <a href="index.php?id=5" class="tag-block">Others</a>

                <a href="admin/dashboard.php" class="dashboard-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                <a href="mailto:anuragsing2503@gmail.com" class="join-us-button">   
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                    Write for us
                </a>
            </nav>
        </div>
    </body>
</html>