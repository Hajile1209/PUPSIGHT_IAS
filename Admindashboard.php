<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | PUPSIGHT</title>
  <link rel="stylesheet" href="admindashstyle.css">
</head>
<body>

  <div class="header">
    <h1>Admin Dashboard - PUPSIGHT</h1>
    <a class="logout-btn" href="logout.php">Logout</a>
  </div>

  <div class="dashboard-container">

    <div class="card">
      <h3>Profile</h3>
      <p>Update your profile information</p>
      <a href="profile.php">Go to Profile</a>
    </div>

    <div class="card">
      <h3>Manage Accounts</h3>
      <p>Create or update user accounts</p>
      <a href="adminmanage.php">Go to Account Management</a>
    </div>

    <div class="card">
      <h3>View Logs</h3>
      <p>View login and activity logs</p>
      <a href="view_logs.php">View Logs</a>
    </div>

    <div class="card">
      <h3>Manage AR Content</h3>
      <p>Upload or update AR content</p>
      <a href="upload_ar_content.php">Manage AR Content</a>
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
        alert("You will be logged out in 5 seconds due to inactivity.");
      }, 25000); // Warn after 25 seconds

      logoutTimer = setTimeout(() => {
        alert("You have been logged out due to inactivity.");
        window.location.href = "logout.php";
      }, 30000); // Logout after 30 seconds
    }

    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onkeydown = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;
  </script>

</body>
</html>