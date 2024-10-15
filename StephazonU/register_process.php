<?php
// Include the database connection file
include 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Simple validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert user into the database
    $sql = "INSERT INTO tblUsers (firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $role);

        // Execute the query
        if ($stmt->execute()) {
            echo "Registration successful! Welcome to Stephazon University, where world domination is just a degree away. Prepare for greatness!";
        } else {
            echo "Error: Could not execute query. " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare query. " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
