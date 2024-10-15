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

// Fetch the list of registered courses
$sql = "SELECT c.courseName, c.courseCode
        FROM tblEnrollments e
        JOIN tblCourses c ON e.courseId = c.id
        WHERE e.userId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Registered Courses</title>
</head>
<body>

<h2>Your Registered Courses</h2>
<ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li><?php echo $row['courseName'] . " (" . $row['courseCode'] . ")"; ?></li>
    <?php endwhile; ?>
</ul>
<form action="process_delete_course.php" method="POST">
    <?php while ($row = $result->fetch_assoc()): ?>
        <input type="checkbox" name="courseIds[]" value="<?php echo $row['courseId']; ?>">
        <?php echo $row['courseName'] . " (" . $row['courseCode'] . ")"; ?><br>
    <?php endwhile; ?>
    <button type="submit">Delete Selected Courses</button>
</form>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
