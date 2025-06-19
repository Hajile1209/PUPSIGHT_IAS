<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_pupsight";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password_hash'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($username) || empty($password) || empty($otp)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password_hash, role, otp_code FROM admin_logins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            if ($otp === $user['otp_code']) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Update last login and clear OTP
                $update = $conn->prepare("UPDATE admin_logins SET otp_code = NULL, last_login = NOW() WHERE id = ?");
                $update->bind_param("i", $user['id']);
                $update->execute();

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: Admindashboard.php");
                    exit;
                } elseif ($user['role'] === 'staff') {
                    header("Location: Staffdashboard.php");
                    exit;
                } else {
                    echo "<script>alert('Unknown role detected.'); window.history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Incorrect OTP.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Username not found.'); window.history.back();</script>";
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
