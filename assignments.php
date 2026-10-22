
<?php
require_once 'config.php';

try {
    $stmt = $pdo->query(
        "SELECT id, title, subject, description, due_date, status, created_at
         FROM assignments
         ORDER BY due_date ASC, id DESC"
    );

    $assignments = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Could not load assignments. Please try again.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Assignments | Assign</title>
</head>
<body>
    <h1>All Assignments</h1>

    <p>
        <a href="add.php">+ Add New Assignment</a>
    </p>

    <?php if (count($assignments) === 0): ?>
        <p>No assignments yet. Add your first assignment!</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $assignment): ?>
                    <?php
                    $isOverdue =
                        $assignment['status'] === 'Pending' &&
                        $assignment['due_date'] < date('Y-m-d');
                    ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $assignment['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars(
                                $assignment['subject'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars(
                                $assignment['due_date'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                            <?php if ($isOverdue): ?>
                                <strong> (OVERDUE)</strong>
                            <?php endif; ?>
                        </td>
                        <td>
    <?= htmlspecialchars(
        $assignment['status'],
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</td>
<td>
    <form action="update_status.php" method="POST">
        <input
            type="hidden"
            name="id"
            value="<?= (int) $assignment['id'] ?>"
        >
        <input
            type="hidden"
            name="status"
            value="<?= $assignment['status'] === 'Completed'
                ? 'Pending'
                : 'Completed' ?>"
        >
        <button type="submit">
            <?= $assignment['status'] === 'Completed'
                ? 'Mark Pending'
                : 'Mark Completed' ?>
        </button>
    </form>
</td>
<td>
    <a href="edit.php?id=<?= (int) $assignment['id'] ?>">
        Edit
    </a>
    
<form action="delete.php" method="POST"
      onsubmit="return confirm('Are you sure you want to delete this assignment?');">
    <input
        type="hidden"
        name="id"
        value="<?= (int) $assignment['id'] ?>"
    >
    <button type="submit">Delete</button>
</form>

</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
