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
    <title>Edit Data Analyst</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php $getUserByID = getUserByID($pdo, $_GET['id']); ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h3 mb-0">Edit Data Analyst Profile</h1>
                    </div>
                    <div class="card-body">
                    <form action="core/handleForms.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="POST">
                        <input type="hidden" name="modified_by" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo htmlspecialchars($getUserByID['first_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo htmlspecialchars($getUserByID['last_name']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($getUserByID['email']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="Male" <?php echo ($getUserByID['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($getUserByID['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($getUserByID['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" name="position" 
                                       value="<?php echo htmlspecialchars($getUserByID['position']); ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="years_of_experience" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="years_of_experience" name="years_of_experience" 
                                       value="<?php echo htmlspecialchars($getUserByID['years_of_experience']); ?>" required min="0">
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="editUserBtn" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>