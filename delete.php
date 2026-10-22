
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: assignments.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: assignments.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'DELETE FROM assignments WHERE id = ?'
    );

    $stmt->execute([$id]);
} catch (PDOException $e) {
    error_log($e->getMessage());
}

header('Location: assignments.php');
exit;
