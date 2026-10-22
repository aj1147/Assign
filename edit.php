
<?php
require_once 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid assignment ID.');
}

$message = '';
$title = '';
$subject = '';
$description = '';
$due_date = '';

try {
    // Load the existing assignment.
    $stmt = $pdo->prepare(
        "SELECT title, subject, description, due_date
         FROM assignments WHERE id = :id"
    );
    $stmt->execute([':id' => $id]);
    $assignment = $stmt->fetch();

    if (!$assignment) {
        http_response_code(404);
        exit('Assignment not found.');
    }

    $title = $assignment['title'];
    $subject = $assignment['subject'];
    $description = $assignment['description'] ?? '';
    $due_date = $assignment['due_date'];

    // Save changes when the form is submitted.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $due_date = trim($_POST['due_date'] ?? '');

        $validDate = false;

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $due_date, $matches)) {
            $validDate = checkdate(
                (int) $matches[2],
                (int) $matches[3],
                (int) $matches[1]
            );
        }

        if ($title === '' || $subject === '' || $due_date === '') {
            $message = 'Title, subject and due date are required.';
        } elseif (mb_strlen($title) > 150 || mb_strlen($subject) > 100) {
            $message = 'Title must be at most 150 characters and subject at most 100.';
        } elseif (!$validDate) {
            $message = 'Please enter a valid due date.';
        } else {
            $stmt = $pdo->prepare(
                "UPDATE assignments
                 SET title = :title,
                     subject = :subject,
                     description = :description,
                     due_date = :due_date
                 WHERE id = :id"
            );

            $stmt->execute([
                ':title' => $title,
                ':subject' => $subject,
                ':description' => $description,
                ':due_date' => $due_date,
                ':id' => $id
            ]);

            header('Location: assignments.php');
            exit;
        }
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Something went wrong while accessing the database.');
}

function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment | Assign</title>
</head>
<body>
    <h1>Edit Assignment</h1>

    <?php if ($message !== ''): ?>
        <p role="alert"><?= escape($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?= (int) $id ?>">
        <label for="title">Assignment Title *</label>
        <input
            type="text"
            id="title"
            name="title"
            maxlength="150"
            required
            value="<?= escape($title) ?>"
        >

        <br><br>

        <label for="subject">Subject *</label>
        <input
            type="text"
            id="subject"
            name="subject"
            maxlength="100"
            required
            value="<?= escape($subject) ?>"
        >

        <br><br>

        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
        ><?= escape($description) ?></textarea>

        <br><br>

        <label for="due_date">Due Date *</label>
        <input
            type="date"
            id="due_date"
            name="due_date"
            required
            value="<?= escape($due_date) ?>"
        >

        <br><br>

        <button type="submit">Save Changes</button>
        <a href="assignments.php">Cancel</a>
    </form>
</body>
</html>
