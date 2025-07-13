<?php

include 'db.php';
$stmt = $conn->prepare('INSERT INTO users (username, name, password) VALUES(?, ?, ?)');
$stmt->bind_param('sss', $username, $name, $hash_password);

$username = '';
$name = '';
$password = '';

$hash_password = password_hash($password, PASSWORD_DEFAULT);

if ($username && $hash_password && $name) {
    $stmt->execute();
}

if ($stmt->affected_rows > 0) {
    echo "row added";
}

?>
