<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | PUPSIGHT</title>
  <link ref="stylesheet" href="adashstyle.css">
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

</body>
</html>