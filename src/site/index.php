<?php
// Include the database connection
include 'db.php';

// Fetch total counts for each entity (optional for overview)
$usersCount = $conn->query("SELECT COUNT(*) FROM User")->fetch_row()[0];
$goalsCount = $conn->query("SELECT COUNT(*) FROM Goal")->fetch_row()[0];
$projectsCount = $conn->query("SELECT COUNT(*) FROM Project")->fetch_row()[0];
$tasksCount = $conn->query("SELECT COUNT(*) FROM Task")->fetch_row()[0];
$calendarsCount = $conn->query("SELECT COUNT(*) FROM Calendar")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Management App - Dashboard</title>
</head>
<body>
    <header>
        <h1>Time Management App</h1>
        <nav>
            <ul>
                <li><a href="user.php">Manage Users</a></li>
                <li><a href="goal.php">Manage Goals</a></li>
                <li><a href="project.php">Manage Projects </a></li>
                <li><a href="task.php">Manage Tasks</a></li>
                <li><a href="calendar.php">Manage Calendars</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Welcome to the Time Management Dashboard</h2>
            <p>Select an option from the menu to manage Users, Goals, Projects, Tasks, and Calendars.</p>
        </section>

        <section>
            <h3>Overview</h3>
            <ul>
                <li><strong>Users:</strong> <?= $usersCount ?> users</li>
                <li><strong>Goals:</strong> <?= $goalsCount ?> goals</li>
                <li><strong>Projects:</strong> <?= $projectsCount ?> projects</li>
                <li><strong>Tasks:</strong> <?= $tasksCount ?> tasks</li>
                <li><strong>Calendars:</strong> <?= $calendarsCount ?> calendars</li>
            </ul>
        </section>
    </main>
    
</body>
</html>

