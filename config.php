<?php
$host = '127.0.0.1';
$dbname = 'assignment_tracker';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo "Database connected successfully!";
} catch (PDOException $e) {
    exit("Database connection failed. Check your database settings.");
}
