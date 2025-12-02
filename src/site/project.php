<?php
include 'db.php';

// Insert logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_project'])) {
    $userID = $_POST['userID'];
    $projectName = $_POST['project_name'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $priority = $_POST['priority'];
    $is_complete = ($_POST['is_complete'] === 'true') ? 1 : 0; // Convert to 1 or 0

    // Ensure the dates are in the correct format
    $startDate = date('Y-m-d', strtotime(str_replace('/', '-', $start)));  // Ensure the correct format for the start date
    $endDate = date('Y-m-d', strtotime(str_replace('/', '-', $end)));      // Same for end date

    $stmt = $conn->prepare("INSERT INTO Project (userID, project_name, start, end, priority, is_complete) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $userID, $projectName, $startDate, $endDate, $priority, $is_complete);
    $stmt->execute();
    echo "Project created successfully!";
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_project'])) {
    $projectID = $_POST['projectID'];
    $userID = $_POST['userID'];
    $projectName = $_POST['project_name'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $priority = $_POST['priority'];
    $is_complete = ($_POST['is_complete'] === 'true') ? 1 : 0; // Convert to 1 or 0

    // Ensure the dates are in the correct format
    $startDate = date('Y-m-d', strtotime($start));  // Ensure the correct format for the start date
    $endDate = date('Y-m-d', strtotime($end));      // Same for end date


    $stmt = $conn->prepare("UPDATE Project SET userID = ?, project_name = ?, start = ?, end = ?, priority = ?, is_complete = ? WHERE projectID = ?");
    $stmt->bind_param("issssii", $userID, $projectName, $start, $end, $priority, $is_complete, $projectID);
    $stmt->execute();
    echo "Project updated successfully!";
}

// Delete logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_project'])) {
    $projectID = $_POST['projectID'];

    $stmt = $conn->prepare("DELETE FROM Project WHERE projectID = ?");
    $stmt->bind_param("i", $projectID);
    $stmt->execute();
    echo "Project deleted successfully!";
}

// Fetch projects and users for dropdown
$projects = $conn->query("SELECT * FROM Project");
$users = $conn->query("SELECT * FROM User");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Project Management</title>
</head>
<body>
    <h1>Manage Projects</h1>

    <!-- Create Project Form -->
    <h2>Create Project</h2>
    <form method="POST">
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['first_name'] ?> <?= $row['last_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="project_name">Project Name:</label>
        <input type="text" name="project_name" required>
        <label for="start">Start Date:</label>
        <input type="date" name="start" required>
        <label for="end">End Date:</label>
        <input type="date" name="end" required>
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
        <button type="submit" name="create_project">Create</button>
    </form>

    <!-- Update Project Form -->
    <h2>Update Project</h2>
    <form method="POST">
        <label for="projectID">Select Project:</label>
        <select name="projectID" required>
            <?php while ($row = $projects->fetch_assoc()) : ?>
                <option value="<?= $row['projectID'] ?>">Project: <?= $row['project_name'] ?> (UserID: <?= $row['userID'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php
            // Reset the users query result for reuse
            $users->data_seek(0);
            while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['first_name'] ?> <?= $row['last_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="project_name">Project Name:</label>
        <input type="text" name="project_name" required>
        <label for="start">Start Date:</label>
        <input type="date" name="start" required>
        <label for="end">End Date:</label>
        <input type="date" name="end" required>
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
        <button type="submit" name="update_project">Update</button>
    </form>

    <!-- Delete Project Form -->
    <h2>Delete Project</h2>
    <form method="POST">
        <label for="projectID">Select Project:</label>
        <select name="projectID" required>
            <?php
            // Reset the query result for table display
            $projects->data_seek(0);
            while ($row = $projects->fetch_assoc()) : ?>
                <option value="<?= $row['projectID'] ?>">Project: <?= $row['project_name'] ?> (UserID: <?= $row['userID'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="delete_project">Delete</button>
    </form>

    <!-- Project Table -->
    <h2>Existing Projects</h2>
    <table border="1">
        <tr>
            <th>Project ID</th>
            <th>User ID</th>
            <th>Project Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Priority</th>
            <th>Is Complete</th>
        </tr>
        <?php
        // Reset the query result for table display
        $projects->data_seek(0);
        while ($row = $projects->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['projectID'] ?></td>
                <td><?= $row['userID'] ?></td>
                <td><?= $row['project_name'] ?></td>
                <td><?= $row['start'] ?></td>
                <td><?= $row['end'] ?></td>
                <td><?= $row['priority'] ?></td>
                <td><?= $row['is_complete'] ? 'True' : 'False' ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Home </a>
</body>
</html>
