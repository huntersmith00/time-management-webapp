<?php
include 'db.php';

// Insert logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    $stmt = $conn->prepare("INSERT INTO User (first_name, last_name) VALUES (?, ?)");
    $stmt->bind_param("ss", $firstName, $lastName);
    $stmt->execute();
    echo "User created successfully!";
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $userID = $_POST['userID'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    $stmt = $conn->prepare("UPDATE User SET first_name = ?, last_name = ? WHERE userID = ?");
    $stmt->bind_param("ssi", $firstName, $lastName, $userID);
    $stmt->execute();
    echo "User updated successfully!";
}

// Delete logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $userID = $_POST['userID'];

    $stmt = $conn->prepare("DELETE FROM User WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    echo "User deleted successfully!";
}

// Fetch users for dropdown and table
$users = $conn->query("SELECT * FROM User");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
</head>
<body>
    <h1>Manage Users</h1>

    <!-- Create User Form -->
    <h2>Create User</h2>
    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>
        <button type="submit" name="create_user">Create</button>
    </form>

    <!-- Update User Form -->
    <h2>Update User</h2>
    <form method="POST">
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['first_name'] ?> <?= $row['last_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>
        <button type="submit" name="update_user">Update</button>
    </form>

    <!-- Delete User Form -->
    <h2>Delete User</h2>
    <form method="POST">
        <label for="userID">Select User:</label>
        <select name="userID" required>
            <?php
            // Reset the query result for reuse
            $users->data_seek(0);
            while ($row = $users->fetch_assoc()) : ?>
                <option value="<?= $row['userID'] ?>"><?= $row['first_name'] ?> <?= $row['last_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="delete_user">Delete</button>
    </form>

    <!-- User Table -->
    <h2>Existing Users</h2>
    <table border="1">
        <tr>
            <th>UserID</th>
            <th>First Name</th>
            <th>Last Name</th>
        </tr>
        <?php
        // Reset the query result for table display
        $users->data_seek(0);
        while ($row = $users->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['userID'] ?></td>
                <td><?= $row['first_name'] ?></td>
                <td><?= $row['last_name'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Home </a>
</body>
</html>
