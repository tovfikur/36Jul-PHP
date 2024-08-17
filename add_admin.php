<?php
// Database connection details
$host = '127.0.0.1';
$db   = 'admin';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

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