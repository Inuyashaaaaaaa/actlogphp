<?php 
require_once 'core/dbConfig.php';
require_once 'core/models.php';
session_start();
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
    <title>Data Analyst Employees Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Data Analyst Directory</a>
        <?php if (isset($_SESSION['username'])) { ?>
            <span class="navbar-text text-light">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="activitylogs.php" class="btn btn-info ms-2">Activity Logs</a>
            <a href="core/handleforms.php?logoutAUser=1" class="btn btn-danger ms-2">Logout</a>
        <?php } ?>
        
        
    </div>
</nav>

<div class="container">
    <?php if (isset($_SESSION['message'])) { ?>
        <div class="alert alert-<?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success'; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php } ?>

    <div class="row mb-4">
        <div class="col-md-8">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="GET" class="d-flex gap-2">
                <input type="text" name="searchInput" 
                       class="form-control" 
                       placeholder="Search by name, email, or position..."
                       value="<?php echo isset($_GET['searchInput']) ? htmlspecialchars($_GET['searchInput']) : ''; ?>">
                <button type="submit" name="searchBtn" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
                <?php if (isset($_GET['searchBtn'])) { ?>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                <?php } ?>
            </form>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="insert.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New Data Analyst
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Position</th>
                            <th>Experience</th>
                            <th>Date Added</th>
                            <th>Created By</th>
                            <th>Modified By</th>
                            <th>Date Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_GET['searchBtn'])) {
                            $users = getAllUsers($pdo);
                        } else {
                            $users = searchForAUser($pdo, $_GET['searchInput']);
                        }

                        if ($users && count($users) > 0) {
                            foreach ($users as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                                    <td><?php echo htmlspecialchars($row['years_of_experience']); ?> years</td>
                                    <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                    <td><?php echo htmlspecialchars($row['modified_by']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_modified']); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="edit.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-2"></i>No data analysts found
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
