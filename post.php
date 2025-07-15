<?php

require_once 'inc/db.php';

$msg = '';
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'] ?? 0;

    $stmt = $conn->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    if (!$post) $msg = 'No post found';

} else $msg = 'No post found';

?>

<html>
    <body>
        <?php if ($msg): ?>
            <?= $msg ?>
            <meta http-equiv="refresh" content="3;url=admin/dashboard.php">
        <?php endif; ?>

        <b><?= $post['title'] ?></b><br>
        <?= nl2br(htmlspecialchars($post['content'], ENT_NOQUOTES, 'UTF-8')) ?>
    </body>
</html>