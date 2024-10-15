<?php
// Start session
session_start();

// Include the database connection
include 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        die("Email and password are required.");
    }

    // Prepare the SQL statement to fetch the user
    $sql = "SELECT id, firstName, password FROM tblUsers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $firstName, $hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Store user details in the session
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_firstName'] = $firstName;
            $_SESSION['user_email'] = $email;

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
