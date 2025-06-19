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
    $email = $_POST['email'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (!empty($email) && !empty($fullname) && !empty($otp)) {
        // Save new login
        $stmt = $conn->prepare("INSERT INTO user_logins (email, fullname, otp_code) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $fullname, $otp);

        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            $_SESSION['fullname'] = $fullname;

            header("Location: Userdashboard.php");
            exit;
        } else {
            echo "<script>alert('Failed to save login data.'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
    }
}

$conn->close();
?>
