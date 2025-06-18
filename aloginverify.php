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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password_hash'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($username) || empty($password) || empty($otp)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("SELECT id, username, password_hash, role, otp_code FROM admin_logins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            if ($otp === $user['otp_code']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // // Clear OTP after successful use (optional but recommended)
                // $clear = $conn->prepare("UPDATE admin_logins SET otp_code = NULL, last_login = NOW() WHERE id = ?");
                // $clear->bind_param("i", $user['id']);
                // $clear->execute();

                if ($user['role'] === 'admin') {
                    header("Location: Admindashboard.php");
                } elseif ($user['role'] === 'staff') {
                    header("Location: Staffdashboard.php");
                } else {
                    echo "<script>alert('Unknown user role.'); window.history.back();</script>";
                }
                exit;
            } else {
                echo "<script>alert('Incorrect OTP.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>