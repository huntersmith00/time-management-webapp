<?php
include 'db.php';

// Insert logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_calendar'])) {
    $userID = $_POST['userID'];
    $capacity = $_POST['capacity'];

    $stmt = $conn->prepare("INSERT INTO Calendar (userID, capacity) VALUES (?, ?)");
    $stmt->bind_param("ii", $userID, $capacity);
    $stmt->execute();
    echo "Calendar created successfully!";
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_calendar'])) {
    $calendarID = $_POST['calendarID'];
    $userID = $_POST['userID'];
    $capacity = $_POST['capacity'];

    $stmt = $conn->prepare("UPDATE Calendar SET userID = ?, capacity = ? WHERE calendarID = ?");
    $stmt->bind_param("iii", $userID, $capacity, $calendarID);
    $stmt->execute();
    echo "Calendar updated successfully!";
}

// Delete logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_calendar'])) {
    $calendarID = $_POST['calendarID'];

    $stmt = $conn->prepare("DELETE FROM Calendar WHERE calendarID = ?");
    $stmt->bind_param("i", $calendarID);
    $stmt->execute();
    echo "Calendar deleted successfully!";
}

// Fetch calendars and users for dropdown
$calendars = $conn->query("SELECT * FROM Calendar");
$users = $conn->query("SELECT * FROM User WHERE userID NOT IN (SELECT userID FROM Calendar)");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calendar Management</title>
</head>
<body>
    <h1>Manage Calendars</h1>

    <!-- Create Calendar Form -->
    <h2>Create Calendar</h2>
    <form method="POST">
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['first_name'] ?> <?= $row['last_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" required>
        <button type="submit" name="create_calendar">Create</button>
    </form>

    <!-- Update Calendar Form -->
    <h2>Update Calendar</h2>
    <form method="POST">
        <label for="calendarID">Select Calendar:</label>
        <select name="calendarID" required>
            <?php while ($row = $calendars->fetch_assoc()) : ?>
                <option value="<?= $row['calendarID'] ?>">Calendar ID: <?= $row['calendarID'] ?> (UserID: <?= $row['userID'] ?>)</option>
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
        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" required>
        <button type="submit" name="update_calendar">Update</button>
    </form>

    <!-- Delete Calendar Form -->
    <h2>Delete Calendar</h2>
    <form method="POST">
        <label for="calendarID">Select Calendar:</label>
        <select name="calendarID" required>
            <?php
            // Reset the query result for table display
            $calendars->data_seek(0);
            while ($row = $calendars->fetch_assoc()) : ?>
                <option value="<?= $row['calendarID'] ?>">Calendar ID: <?= $row['calendarID'] ?> (UserID: <?= $row['userID'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="delete_calendar">Delete</button>
    </form>

    <!-- Calendar Table -->
    <h2>Existing Calendars</h2>
    <table border="1">
        <tr>
            <th>Calendar ID</th>
            <th>User ID</th>
            <th>Capacity</th>
        </tr>
        <?php
        // Reset the query result for table display
        $calendars->data_seek(0);
        while ($row = $calendars->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['calendarID'] ?></td>
                <td><?= $row['userID'] ?></td>
                <td><?= $row['capacity'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Home </a>
</body>
</html>
