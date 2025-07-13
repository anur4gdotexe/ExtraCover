<?php
session_start();
require_once '../inc/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    //creating a prepared statement
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['userId'] = $user['id'];

            header('Location: dashboard.php');        
        }
        else $error = 'invalid password';
    }
    else $error = 'user not found';
}
?>

<html>
    <body>
        <h2>Admin Login</h2>

        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="username" required><br><br>
            <input type="password" name="password" placeholder="password" required><br><br>

            <button type="submit">SUBMIT</button>
        </form>
    </body>
</html>