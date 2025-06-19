<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Dashboard | PUPSIGHT</title>
  <link rel="stylesheet" href="staffdashstyle.css">
</head>
<body>

  <div class="header">
    <h1>Staff Dashboard - PUPSIGHT</h1>
    <a class="logout-btn" href="logout.php">Logout</a>
  </div>

  <div class="dashboard-container">

    <div class="card">
      <h3>Profile</h3>
      <p>Update your profile information</p>
      <a href="profile.php">Go to Profile</a>
    </div>

    <div class="card">
      <h3>View Logs</h3>
      <p>View login and activity logs</p>
      <a href="view_logs.php">View Logs</a>
    </div>

    <div class="card">
      <h3>Manage AR Content</h3>
      <p>Upload or update AR content</p>
      <a href="upload_ar.php">Manage AR Content</a>
    </div>

  </div>

    <!-- SESSION TIMEOUT SCRIPT -->
  <script>
    let logoutTimer;
    let warningTimer;

    function resetTimer() {
      clearTimeout(logoutTimer);
      clearTimeout(warningTimer);

      warningTimer = setTimeout(() => {
        alert("You will be logged out in 30 seconds due to inactivity.");
      }, 30000); // Warn after 30 seconds

      logoutTimer = setTimeout(() => {
        alert("You have been logged out due to inactivity.");
        window.location.href = "logout.php";
      }, 60000); // Logout after 1 minute
    }

    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onkeydown = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;
  </script>

</body>
</html>