<?php
// Include database connection
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Check if any courses were selected
if (!empty($_POST['courseIds'])) {
    foreach ($_POST['courseIds'] as $courseId) {
        // Insert the registration into the database
        $sql = "INSERT INTO tblEnrollments (userId, courseId) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to the dashboard after successful registration
    header("Location: dashboard.php");
    exit();
} else {
    echo "No courses were selected.";
}

$conn->close();
?>
