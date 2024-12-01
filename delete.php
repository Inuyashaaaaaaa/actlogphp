<?php 
require_once 'core/dbConfig.php';
require_once 'core/models.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Data Analyst</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Data Analyst Directory</a>
        </div>
    </nav>

    <div class="container">
        <?php $getUserByID = getUserByID($pdo, $_GET['id']); ?>
        
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text text-danger mb-4">Are you sure you want to delete this data analyst profile? This action cannot be undone.</p>
                
                <dl class="row">
                    <dt class="col-sm-3">Full Name</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($getUserByID['first_name'] . ' ' . $getUserByID['last_name']); ?></dd>
                    
                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($getUserByID['email']); ?></dd>
                    
                    <dt class="col-sm-3">Gender</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($getUserByID['gender']); ?></dd>
                    
                    <dt class="col-sm-3">Position</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($getUserByID['position']); ?></dd>
                    
                    <dt class="col-sm-3">Experience</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($getUserByID['years_of_experience']); ?> years</dd>
                    
                    <dt class="col-sm-3">Date Added</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($getUserByID['date_added']); ?></dd>
                </dl>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <form action="core/handleForms.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="POST" style="display: inline;">
                        <button type="submit" name="deleteUserBtn" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Confirm Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>