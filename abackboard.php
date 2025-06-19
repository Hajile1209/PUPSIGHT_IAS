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

// HANDLE DELETE
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM admin_logins WHERE id = $delete_id");
    header("Location: Admindashboard.php");
    exit;
}

// HANDLE UPDATE
if (isset($_POST['update_id'])) {
    $update_id = intval($_POST['update_id']);
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $check = $conn->prepare("SELECT password_hash FROM admin_logins WHERE id = ?");
    $check->bind_param("i", $update_id);
    $check->execute();
    $result = $check->get_result();
    $user = $result->fetch_assoc();
    $check->close();

    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        echo "<script>alert('Incorrect old password.'); window.history.back();</script>";
        exit;
    }

    if (!empty($new_password)) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin_logins SET username=?, role=?, password_hash=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $role, $hashed, $update_id);
    } else {
        $stmt = $conn->prepare("UPDATE admin_logins SET username=?, role=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $role, $update_id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: Admindashboard.php");
    exit;
}

// HANDLE REGISTRATION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update_id'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['user_type']);

    $check = $conn->prepare("SELECT * FROM admin_logins WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $existing = $check->get_result();
    $check->close();

    if ($existing->num_rows > 0) {
        echo "<script>alert('Username already exists. Please choose a different one.'); window.history.back();</script>";
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admin_logins (username, password_hash, role, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $username, $hashed, $role);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Account created successfully!'); window.location.href='Admindashboard.php';</script>";
    exit;
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
