<?php
require_once 'dbConfig.php';

/**
 * User Authentication Functions
 */

// Register a new user
function registerNewUser($pdo, $username, $password) {
    // Check if the username already exists
    $checkUserSql = "SELECT * FROM user_passwords WHERE username = ?";
    $checkUserStmt = $pdo->prepare($checkUserSql);
    $checkUserStmt->execute([$username]);

    if ($checkUserStmt->rowCount() == 0) {
        // Insert new user
        $sql = "INSERT INTO user_passwords (username, password) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$username, $password])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // Username already exists
    }
}


// Login an existing user
function loginUser($pdo, $username, $password) {
    $sql = "SELECT * FROM user_passwords WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['password'] === $password) { // Verify plain-text password (not secure; use password_hash in real projects)
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['username'] = $user['username'];
            return true;
        } else {
            $_SESSION['message'] = "Incorrect password.";
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = "Username not found.";
        $_SESSION['message_type'] = 'danger';
    }

    return false;
}

// Get all registered users
function getAllRegisteredUsers($pdo) {
    $sql = "SELECT * FROM user_passwords";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Existing Functions for Managing Data Analyst Employees
 */

// Function for adding a data analyst (index.php, insert.php)
function addDataAnalyst($pdo, $first_name, $last_name, $email, $gender, $position, $years_of_experience, $created_by, $modified_by) {
    $sql = "INSERT INTO data_analyst_employees (first_name, last_name, email, gender, position, years_of_experience, created_by, modified_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$first_name, $last_name, $email, $gender, $position, $years_of_experience, $created_by, $modified_by]);

    // If insertion is successful, log the activity
    if ($result) {
        // Get the last inserted employee details to log the activity
        $employee_id = $pdo->lastInsertId();
        insertActivityLog($pdo, 'INSERT', $employee_id, $first_name, $last_name, $email, $position, $created_by);
    }

    return $result;
}




function getAllUsers($pdo) {
    $sql = "SELECT * FROM data_analyst_employees ORDER BY date_added DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserByID($pdo, $id) {
    $sql = "SELECT * FROM data_analyst_employees WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function editUser($pdo, $first_name, $last_name, $email, $gender, $position, $years_of_experience, $modified_by, $id) {
    $sql = "UPDATE data_analyst_employees 
            SET first_name = ?, last_name = ?, email = ?, gender = ?, position = ?, years_of_experience = ?, 
                modified_by = ?, date_modified = CURRENT_TIMESTAMP
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$first_name, $last_name, $email, $gender, $position, $years_of_experience, $modified_by, $id]);

    if ($result) {
        // Log the update operation
        insertActivityLog($pdo, 'UPDATE', $id, $first_name, $last_name, $email, $position, $modified_by);
    }

    return $result;
}



function deleteUser($pdo, $id) {
    // Retrieve the employee details before deleting
    $employee = getUserByID($pdo, $id);

    if ($employee) {
        // Log the delete operation
        insertActivityLog($pdo, 'DELETE', $id, $employee['first_name'], $employee['last_name'], $employee['email'], $employee['position'], $_SESSION['username']);
        
        // Proceed to delete the employee
        $sql = "DELETE FROM data_analyst_employees WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    return false;
}


function searchForAUser($pdo, $searchTerm) {
    $searchTerm = "%$searchTerm%";
    
    // Log the search operation
    insertActivityLog($pdo, 'SEARCH', null, null, null, null, null, $_SESSION['username']);

    $sql = "SELECT * FROM data_analyst_employees 
            WHERE first_name LIKE ? 
            OR last_name LIKE ? 
            OR email LIKE ? 
            OR position LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//activity log
function insertActivityLog($pdo, $operation, $employee_id, $first_name, $last_name, $email, $position, $modified_by) {
    $sql = "INSERT INTO activity_logs (operation, employee_id, first_name, last_name, email, position, modified_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$operation, $employee_id, $first_name, $last_name, $email, $position, $modified_by]);
}

function getActivityLogs($pdo) {
    $sql = "SELECT * FROM activity_logs ORDER BY date_added DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs 
			ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}



?>
