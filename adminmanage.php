<?php
session_start();

$host ="localhost";
$usern ="root";
$pass ="";
$dbname ="db_pupsight";

$conn = new mysqli($host, $usern, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// FETCH USERS
$result = $conn->query("SELECT * FROM admin_logins WHERE role IN ('admin','staff')");

// FETCH ACCOUNT TO EDIT
$edit_account = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $edit_result = $conn->query("SELECT * FROM admin_logins WHERE id = $edit_id LIMIT 1");
    $edit_account = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Accounts | PUPSIGHT</title>
  <link rel="stylesheet" href="amanagestyle.css">
</head>
<body>
<div class="container">
  <h2><?= $edit_account ? 'Edit Account' : 'Add New Account' ?></h2>
  <form action="abackboard.php" method="post">
    <?php if ($edit_account): ?>
      <input type="hidden" name="update_id" value="<?= $edit_account['id'] ?>">
      <input type="text" name="username" value="<?= htmlspecialchars($edit_account['username']) ?>" required>
      <input type="password" name="old_password" placeholder="Old Password" required>
      <input type="password" name="new_password" placeholder="New Password (leave blank to keep current)">
      <select name="role" required>
        <option value="admin" <?= $edit_account['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="staff" <?= $edit_account['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
      </select>
      <button type="submit">Update Account</button>
    <?php else: ?>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <select name="user_type" required>
        <option value="" disabled selected>Select User Type</option>
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
      </select>
      <button type="submit">Save Account</button>
    <?php endif; ?>
  </form>

  <h3 style="margin-top: 40px; color: #800000;">Existing Users</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= ucfirst($row['role']) ?></td>
          <td class="action-buttons">
            <a href="?edit_id=<?= $row['id'] ?>">Edit</a>
            <a href="abackboard.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this account?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="4">No users found.</td></tr>
    <?php endif; ?>
  </table>
</div>
</body>
</html>
<?php $conn->close(); ?>