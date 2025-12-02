<?php
include 'db.php';

// Insert logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_goal'])) {
    $userID = $_POST['userID'];
    $goalName = $_POST['goal_name'];
    $capacity = $_POST['capacity'];
    $priority = $_POST['priority'];
    $is_complete = ($_POST['is_complete'] === 'true') ? 1 : 0; // Convert to 1 or 0


    $stmt = $conn->prepare("INSERT INTO Goal (userID, goal_name, capacity, priority, is_complete) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isisi", $userID, $goalName, $capacity, $priority, $is_complete);
    $stmt->execute();
    echo "Goal created successfully!";
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_goal'])) {
    $goalID = $_POST['goalID'];
    $userID = $_POST['userID'];
    $goalName = $_POST['goal_name'];
    $capacity = $_POST['capacity'];
    $priority = $_POST['priority'];
    $is_complete = ($_POST['is_complete'] === 'true') ? 1 : 0; // Convert to 1 or 0

    $stmt = $conn->prepare("UPDATE Goal SET userID = ?, goal_name = ?, capacity = ?, priority = ?, is_complete = ? WHERE goalID = ?");
    $stmt->bind_param("isisii", $userID, $goalName, $capacity, $priority, $is_complete, $goalID);
    $stmt->execute();
    echo "Goal updated successfully!";
}

// Delete logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_goal'])) {
    $goalID = $_POST['goalID'];

    $stmt = $conn->prepare("DELETE FROM Goal WHERE goalID = ?");
    $stmt->bind_param("i", $goalID);
    $stmt->execute();
    echo "Goal deleted successfully!";
}

// Fetch goals and users for dropdown
$goals = $conn->query("SELECT * FROM Goal");
$users = $conn->query("SELECT * FROM User");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Goal Management</title>
</head>
<body>
    <h1>Manage Goals</h1>

    <!-- Create Goal Form -->
    <h2>Create Goal</h2>
    <form method="POST">
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['first_name'] ?> <?= $row['last_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="goal_name">Goal Name:</label>
        <input type="text" name="goal_name" required>
        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" required>
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
        <button type="submit" name="create_goal">Create</button>
    </form>

    <!-- Update Goal Form -->
    <h2>Update Goal</h2>
    <form method="POST">
        <label for="goalID">Select Goal:</label>
        <select name="goalID" required>
            <?php while ($row = $goals->fetch_assoc()) : ?>
                <option value="<?= $row['goalID'] ?>">Goal: <?= $row['goal_name'] ?> (UserID: <?= $row['userID'] ?>)</option>
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
        <label for="goal_name">Goal Name:</label>
        <input type="text" name="goal_name" required>
        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" required>
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
        <button type="submit" name="update_goal">Update</button>
    </form>

    <!-- Delete Goal Form -->
    <h2>Delete Goal</h2>
    <form method="POST">
        <label for="goalID">Select Goal:</label>
        <select name="goalID" required>
            <?php
            // Reset the query result for table display
            $goals->data_seek(0);
            while ($row = $goals->fetch_assoc()) : ?>
                <option value="<?= $row['goalID'] ?>">Goal: <?= $row['goal_name'] ?> (UserID: <?= $row['userID'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="delete_goal">Delete</button>
    </form>

    <!-- Goal Table -->
    <h2>Existing Goals</h2>
    <table border="1">
        <tr>
            <th>Goal ID</th>
            <th>User ID</th>
            <th>Goal Name</th>
            <th>Capacity</th>
            <th>Priority</th>
            <th>Is Complete</th>
        </tr>
        <?php
        // Reset the query result for table display
        $goals->data_seek(0);
        while ($row = $goals->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['goalID'] ?></td>
                <td><?= $row['userID'] ?></td>
                <td><?= $row['goal_name'] ?></td>
                <td><?= $row['capacity'] ?></td>
                <td><?= $row['priority'] ?></td>
                <td><?= $row['is_complete'] ? 'True' : 'False' ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Home </a>
</body>
</html>
