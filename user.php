<?php

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Hash the password using PHP
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert data
        try {
            $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword
            ]);
            echo 'User created successfully.';
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];

        // Delete data
        try {
            $stmt = $pdo->prepare("DELETE FROM admins WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo 'User deleted successfully.';
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($action === 'logout') {
        // Start session and destroy it
        session_start();
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
?>
