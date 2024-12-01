<?php
require_once 'dbConfig.php';
require_once 'models.php';

session_start();

// User registration logic
// User registration logic
if (isset($_POST['registerUserBtn'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // Secure the password using SHA1

    if (!empty($username) && !empty($password)) {
        $insertQuery = registerNewUser($pdo, $username, $password);

        if ($insertQuery) {
            $_SESSION['message'] = "Registration successful!";
            $_SESSION['message_type'] = "success";
            header("Location: ../login.php");
        } else {
            $_SESSION['message'] = "Registration failed. Username may already exist.";
            $_SESSION['message_type'] = "danger";
            header("Location: ../register.php");
        }
    } else {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['message_type'] = "warning";
        header("Location: ../register.php");
    }
    exit();
}


// Login logic
if (isset($_POST['loginUserBtn'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // Match the stored hashed password

    if (!empty($username) && !empty($password)) {
        if (loginUser($pdo, $username, $password)) {
            $_SESSION['username'] = $username;
            $_SESSION['message'] = "Welcome, $username!";
            $_SESSION['message_type'] = "success";
            header("Location: ../index.php");
        } else {
            $_SESSION['message'] = "Invalid username or password.";
            $_SESSION['message_type'] = "danger";
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['message'] = "Please fill out all fields.";
        $_SESSION['message_type'] = "warning";
        header("Location: ../login.php");
    }
    exit();
}

// Logout logic
if (isset($_GET['logoutAUser'])) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
// Insert new Data Analyst profile
if (isset($_POST['insertUserBtn'])) {
    $created_by = $_SESSION['username']; // Get the username of the logged-in user

    // Pass the current username as created_by and modified_by
    $insertUser = addDataAnalyst(
        $pdo,
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['gender'],
        $_POST['position'],
        $_POST['years_of_experience'],
        $_SESSION['username'], // modified_by
        $created_by // created_by
    );

    if ($insertUser) {
        $_SESSION['message'] = "Data Analyst successfully added!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error adding Data Analyst.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: ../index.php");
    exit();
}



// Edit existing user
if (isset($_POST['editUserBtn'])) {
    $editUser = editUser(
        $pdo,
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['gender'],
        $_POST['position'],
        $_POST['years_of_experience'],
        $_SESSION['username'], // Pass the current username as `modified_by`
        $_GET['id']
    );

    if ($editUser) {
        $_SESSION['message'] = "Data Analyst profile successfully updated!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating Data Analyst profile.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: ../index.php");
    exit();
}

// Delete user
if (isset($_POST['deleteUserBtn'])) {
    $deleteUser = deleteUser($pdo, $_GET['id']);

    if ($deleteUser) {
        $_SESSION['message'] = "Data Analyst profile successfully deleted!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting Data Analyst profile.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: ../index.php");
    exit();
}

// Search for users
if (isset($_GET['searchBtn'])) {
    $searchResults = searchForAUser($pdo, $_GET['searchInput']);
    if ($searchResults) {
        foreach ($searchResults as $row) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['first_name']) . "</td>
                    <td>" . htmlspecialchars($row['last_name']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['gender']) . "</td>
                    <td>" . htmlspecialchars($row['position']) . "</td>
                    <td>" . htmlspecialchars($row['years_of_experience']) . "</td>
                    <td>" . htmlspecialchars($row['date_added']) . "</td>
                    <td>" . htmlspecialchars($row['modified_by']) . "</td>
                    <td>" . htmlspecialchars($row['date_modified']) . "</td>
                    <td>
                        <a href='edit.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-edit'>Edit</a>
                        <a href='delete.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-delete'>Delete</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='10' class='text-center'>No results found</td></tr>";
    }
}
?>
