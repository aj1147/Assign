
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: assignments.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';

if (!$id || !in_array($status, ['Pending', 'Completed'], true)) {
    http_response_code(400);
    exit('Invalid assignment ID or status.');
}

try {
    $stmt = $pdo->prepare(
        "UPDATE assignments SET status = :status WHERE id = :id"
    );

    $stmt->execute([
        ':status' => $status,
        ':id' => $id
    ]);

    header('Location: assignments.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Could not update assignment status.');
}
