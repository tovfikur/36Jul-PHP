<?php
// Include the database connection
require 'admin_db.php';

// Data to insert
$username = 'admin';
$password = 'admin';

// Hash the password using PHP
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert hashed password into the database
try {
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    $stmt->execute([
        ':username' => $username,
        ':password' => $hashedPassword
    ]);
    echo 'Admin user inserted successfully.';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>