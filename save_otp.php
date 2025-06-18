<?php
// save_otp.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (!empty($username) && !empty($otp)) {
        $conn = new mysqli("localhost", "root", "", "db_pupsight");
        if ($conn->connect_error) {
            http_response_code(500);
            exit("Connection failed");
        }

        $stmt = $conn->prepare("UPDATE admin_logins SET otp_code = ? WHERE username = ?");
        $stmt->bind_param("ss", $otp, $username);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
?>