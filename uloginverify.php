<?php
// Connect to MySQL
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_pupsight";  

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (!empty($email) && !empty($fullname) && !empty($otp)) {
        $stmt = $conn->prepare("INSERT INTO user_logins (email, fullname, otp_code) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $fullname, $otp);

        if ($stmt->execute()) {
            echo "Login data saved successfully!";
            header("Location: Userdashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

$conn->close();
?>