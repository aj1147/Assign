
<?php
require_once 'config.php';

$message = '';
$messageType = '';

$title = '';
$subject = '';
$description = '';
$due_date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $due_date = trim($_POST['due_date'] ?? '');

    if ($title === '' || $subject === '' || $due_date === '') {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } elseif (
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due_date) ||
        !checkdate(
            (int) substr($due_date, 5, 2),
            (int) substr($due_date, 8, 2),
            (int) substr($due_date, 0, 4)
        )
    ) {
        $message = 'Please enter a valid due date.';
        $messageType = 'error';
    } else {
        try {
            $sql = "INSERT INTO assignments
                    (title, subject, description, due_date)
                    VALUES (:title, :subject, :description, :due_date)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':subject' => $subject,
                ':description' => $description,
                ':due_date' => $due_date
            ]);

            $message = 'Assignment added successfully!';
            $messageType = 'success';

            $title = '';
            $subject = '';
            $description = '';
            $due_date = '';
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $message = 'Could not save the assignment. Please try again.';
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/style.css">
<script src="assets/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assignment | Assign</title>
</head>
<body>
    <h1>Add Assignment</h1>

    <?php if ($message !== ''): ?>
        <p role="status">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="add.php">
        <label for="title">Assignment Title *</label>
        <input
            type="text"
            id="title"
            name="title"
            maxlength="150"
            required
            value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
        >

        <br><br>

        <label for="subject">Subject *</label>
        <input
            type="text"
            id="subject"
            name="subject"
            maxlength="100"
            required
            value="<?= htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') ?>"
        >

        <br><br>

        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
        ><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>

        <br><br>

        <label for="due_date">Due Date *</label>
        <input
            type="date"
            id="due_date"
            name="due_date"
            required
            value="<?= htmlspecialchars($due_date, ENT_QUOTES, 'UTF-8') ?>"
        >

        <br><br>

        <button type="submit">Save Assignment</button>
    </form>
</body>
</html>
