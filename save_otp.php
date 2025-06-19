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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $otp = $_POST['otp'] ?? '';
    // Only proceed if both username and OTP are not empty
    if (!empty($username) && !empty($otp)) {
        $stmt = $conn->prepare("UPDATE admin_logins SET otp_code = ? WHERE username = ?");
        $stmt->bind_param("ss", $otp, $username);
        $stmt->execute();
        $stmt->close();
        echo "OTP saved.";
    } else {
        echo "Missing fields.";
    }
}

$conn->close();
?>