<?php
session_start();
$host = "localhost";
$usern = "root";
$pass = "";
$dbname = "db_pupsight";

$conn = new mysqli($host, $usern, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add New Account
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'], $_POST['user_type']) && empty($_POST['update_id'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // HASHING password
    $role = $_POST['user_type'];

    // Check if username already exists
    $check = $conn->prepare("SELECT id FROM admin_logins WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Username already exists!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO admin_logins (username, password_hash, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        if ($stmt->execute()) {
            echo "<script>alert('Account created successfully!'); window.location.href='amanageaccount.php';</script>";
        } else {
            echo "<script>alert('Error saving account.');</script>";
        }
    }
}

// Edit Account (update username, password if given)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Fetch current user data
    $stmt = $conn->prepare("SELECT password_hash FROM admin_logins WHERE id = ?");
    $stmt->bind_param("i", $update_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        echo "<script>alert('Old password is incorrect!');</script>";
    } else {
        if (!empty($new_password)) {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin_logins SET username=?, password_hash=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $new_password_hashed, $role, $update_id);
        } else {
            $stmt = $conn->prepare("UPDATE admin_logins SET username=?, role=? WHERE id=?");
            $stmt->bind_param("ssi", $username, $role, $update_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Account updated successfully!'); window.location.href='amanageaccount.php';</script>";
        } else {
            echo "<script>alert('Failed to update account.');</script>";
        }
    }
}

// Delete Account
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM admin_logins WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Account deleted successfully!'); window.location.href='amanageaccount.php';</script>";
    } else {
        echo "<script>alert('Failed to delete account.');</script>";
    }
}

// Fetch account if editing
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM admin_logins WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_account = $stmt->get_result()->fetch_assoc();
}
?>