<?php
// Database connection parameters
$host = 'localhost';
$db = 'admin'; // Replace with your actual database name
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

// Create a Data Source Name (DSN) for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create a PDO instance for MySQL database connection
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode(['status' => 'failed', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}
