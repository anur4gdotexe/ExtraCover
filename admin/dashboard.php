<?php
session_start();

$msg = '';
if (isset($_SESSION['username'])) {
    require_once '../inc/db.php';
    
    $userId = (int)$_SESSION['userId'];
    $stmt = $conn->prepare('SELECT * FROM posts WHERE author_id = ? ORDER BY created_at DESC');
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $result = $stmt->get_result();
}
else $msg = 'Invalid session';

?>

<html>
    <body>
        <?php if ($msg): ?>
            <?php ob_clean(); ?>

            <p style="color:red"><?= $msg ?></p>
            <meta http-equiv="refresh" content="3;url=login.php">
        <?php endif; ?>

        Hello, <?= $_SESSION['username'] ?><br><br>
        <a href="create.php">create</a> | <a href="logout.php">log out</a>
        <hr>
        
        <!-- a list of posts by the user -->
        <h3>All Posts</h3>
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><a href="../post.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this post?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </body>
</html>