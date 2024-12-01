<?php  
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar (same as index.php) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Data Analyst Directory</a>
            <?php if (isset($_SESSION['username'])) { ?>
                <span class="navbar-text text-light">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="index.php" class="btn btn-info ms-2">Home</a>
                <a href="core/handleforms.php?logoutAUser=1" class="btn btn-danger ms-2">Logout</a>
            <?php } ?>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Activity Logs</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Log ID</th>
                        <th>Operation</th>
                        <th>Employee ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Modified By</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all activity logs from the database
                    $getAllActivityLogs = getAllActivityLogs($pdo);

                    // Display each log in a table row
                    foreach ($getAllActivityLogs as $row) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['log_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['operation']); ?></td>
                            <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['position']); ?></td>
                            <td><?php echo htmlspecialchars($row['modified_by']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper (for any Bootstrap JS functionality like dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
