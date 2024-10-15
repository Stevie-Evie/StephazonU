<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db_connection.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['courseId'])) {
    $courseId = $_POST['courseId'];

    // Delete the course from the user's enrollment
    $sql = "DELETE FROM tblEnrollments WHERE userId = ? AND courseId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $courseId);

    if ($stmt->execute()) {
        // Redirect back to the dashboard after deletion
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting the course.";
    }

    $stmt->close();
} else {
    echo "No course selected.";
    header("Location: dashboard.php");
}

$conn->close();
?>
