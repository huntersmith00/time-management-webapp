<?php
include 'db.php';

// Insert logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_task'])) {
    $userID = $_POST['userID'];
    $goalID = $_POST['goalID'] !== "None" ? $_POST['goalID'] : null;
    $projectID = $_POST['projectID'] !== "None" ? $_POST['projectID'] : null;
    $taskName = $_POST['task_name'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $priority = $_POST['priority'];
    $is_complete = ($_POST['is_complete'] === 'true') ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO Task (userID, goalID, projectID, task_name, start, end, priority, is_complete) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisiisi", $userID, $goalID, $projectID, $taskName, $start, $end, $priority, $is_complete);
    $stmt->execute();
    echo "Task created successfully!";
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $taskID = $_POST['taskID'];
    $userID = $_POST['userID'];
    $goalID = $_POST['goalID'] !== "None" ? $_POST['goalID'] : null;
    $projectID = $_POST['projectID'] !== "None" ? $_POST['projectID'] : null;
    $taskName = $_POST['task_name'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $priority = $_POST['priority'];
    $is_complete = ($_POST['is_complete'] === 'true') ? 1 : 0;

    $stmt = $conn->prepare("UPDATE Task SET userID = ?, goalID = ?, projectID = ?, task_name = ?, start = ?, end = ?, priority = ?, is_complete = ? WHERE taskID = ?");
    $stmt->bind_param("iiisiisii", $userID, $goalID, $projectID, $taskName, $start, $end, $priority, $is_complete, $taskID);
    $stmt->execute();
    echo "Task updated successfully!";
}

// Delete logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_task'])) {
    $taskID = $_POST['taskID'];

    $stmt = $conn->prepare("DELETE FROM Task WHERE taskID = ?");
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    echo "Task deleted successfully!";
}

// Fetch tasks, users, goals, and projects for dropdown
$tasks = $conn->query("SELECT * FROM Task");
$users = $conn->query("SELECT userID, CONCAT(first_name, ' ', last_name) AS name FROM User");
$goals = $conn->query("SELECT goalID, goal_name FROM Goal");
$projects = $conn->query("SELECT projectID, project_name FROM Project");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Management</title>
</head>
<body>
    <h1>Manage Tasks</h1>

    <!-- Create Task Form -->
    <h2>Create Task</h2>
    <form method="POST">
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="goalID">Goal:</label>
        <select name="goalID">
            <option value="None">None</option>
            <?php while ($row = $goals->fetch_assoc()) : ?>
                <option value="<?= $row['goalID'] ?>"><?= $row['goal_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="projectID">Project:</label>
        <select name="projectID">
            <option value="None">None</option>
            <?php while ($row = $projects->fetch_assoc()) : ?>
                <option value="<?= $row['projectID'] ?>"><?= $row['project_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="task_name">Task Name:</label>
        <input type="text" name="task_name" required>
        <label for="start">Start:</label>
        <input type="number" name="start" required>
        <label for="end">End:</label>
        <input type="number" name="end" required>
        <label for="priority">Priority:</label>
        <select name="priority" required>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>
        <label for="is_complete">Is Complete:</label>
        <select name="is_complete" required>
            <option value="true">True</option>
            <option value="false">False</option>
        </select>
        <button type="submit" name="create_task">Create</button>
    </form>

    <!-- Update Task Form -->
    <h2>Update Task</h2>
    <form method="POST">
        <label for="taskID">Select Task:</label>
        <select name="taskID" required>
            <?php while ($row = $tasks->fetch_assoc()) : ?>
                <option value="<?= $row['taskID'] ?>">Task: <?= $row['task_name'] ?> (UserID: <?= $row['userID'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php $users->data_seek(0); while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="goalID">Goal:</label>
        <select name="goalID">
            <option value="None">None</option>
            <?php $goals->data_seek(0); while ($row = $goals->fetch_assoc()) : ?>
                <option value="<?= $row['goalID'] ?>"><?= $row['goal_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="projectID">Project:</label>
        <select name="projectID">
            <option value="None">None</option>
            <?php $projects->data_seek(0); while ($row = $projects->fetch_assoc()) : ?>
                <option value="<?= $row['projectID'] ?>"><?= $row['project_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="task_name">Task Name:</label>
        <input type="text" name="task_name" required>
        <label for="start">Start:</label>
        <input type="number" name="start" required>
        <label for="end">End:</label>
        <input type="number" name="end" required>
        <label for="priority">Priority:</label>
        <select name="priority" required>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>
        <label for="is_complete">Is Complete:</label>
        <select name="is_complete" required>
            <option value="true">True</option>
            <option value="false">False</option>
        </select>
        <button type="submit" name="update_task">Update</button>
    </form>

    <!-- Delete Task Form -->
    <h2>Delete Task</h2>
    <form method="POST">
        <label for="taskID">Select Task:</label>
        <select name="taskID" required>
            <?php $tasks->data_seek(0); while ($row = $tasks->fetch_assoc()) : ?>
                <option value="<?= $row['taskID'] ?>">Task: <?= $row['task_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="delete_task">Delete</button>
    </form>

    <!-- Task Table -->
    <h2>Existing Tasks</h2>
    <table border="1">
        <tr>
            <th>Task ID</th>
            <th>User ID</th>
            <th>Goal ID</th>
            <th>Project ID</th>
            <th>Task Name</th>
            <th>Start</th>
            <th>End</th>
            <th>Priority</th>
            <th>Is Complete</th>
        </tr>
        <?php $tasks->data_seek(0); while ($row = $tasks->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['taskID'] ?></td>
                <td><?= $row['userID'] ?></td>
                <td><?= $row['goalID'] ?></td>
                <td><?= $row['projectID'] ?></td>
                <td><?= $row['task_name'] ?></td>
                <td><?= $row['start'] ?></td>
                <td><?= $row['end'] ?></td>
                <td><?= $row['priority'] ?></td>
                <td><?= $row['is_complete'] ? 'True' : 'False' ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Home</a>
</body>
</html>
