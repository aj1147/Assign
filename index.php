
<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("
        SELECT
            COUNT(*) AS total,
            COALESCE(SUM(status = 'Pending'), 0) AS pending,
            COALESCE(SUM(status = 'Completed'), 0) AS completed,
            COALESCE(SUM(
                status = 'Pending'
                AND due_date >= CURDATE()
                AND due_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ), 0) AS upcoming
        FROM assignments
    ");

    $stats = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Could not load dashboard statistics.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign | Dashboard</title>
</head>
<body>
    <h1>Assign — Assignment Manager</h1>
    <p>Keep track of your coursework and deadlines.</p>

    <nav>
        <a href="index.php">Dashboard</a> |
        <a href="add.php">Add Assignment</a> |
        <a href="assignments.php">View Assignments</a>
    </nav>

    <hr>

    <h2>Dashboard</h2>

    <section>
        <h3>Total Assignments</h3>
        <p><?= (int) $stats['total'] ?></p>
    </section>

    <section>
        <h3>Pending</h3>
        <p><?= (int) $stats['pending'] ?></p>
    </section>

    <section>
        <h3>Completed</h3>
        <p><?= (int) $stats['completed'] ?></p>
    </section>

    <section>
        <h3>Upcoming Deadlines</h3>
        <p><?= (int) $stats['upcoming'] ?></p>
        <small>Pending assignments due within the next 7 days.</small>
    </section>

    <p><a href="add.php">+ Add a New Assignment</a></p>
    <p><a href="assignments.php">See All Assignments</a></p>
</body>
</html>

