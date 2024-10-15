<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch available courses the student is not enrolled in
$sql = "SELECT c.id, c.courseName, c.courseCode, c.courseDescription
        FROM tblCourses c
        LEFT JOIN tblEnrollments e ON c.id = e.courseId AND e.userId = ?
        WHERE e.courseId IS NULL";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for Courses</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            height: 100%; /* Ensure all cards are the same height */
        }
        .card-body {
            display: flex;
            flex-direction: column;
        }
        .form-check {
            margin-top: auto; /* Push the checkbox to the bottom */
        }
    </style>
</head>
<body>


<div class="jumbotron text-center">
    <h1>Stephazon University Course Registration</h1>
    <p>Select your next path to world domination.</p>
</div>

<div class="container mt-4">
    <h2>Available Courses</h2>
    <form action="process_register.php" method="POST">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="card mb-4 h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $row['courseName'] . " (" . $row['courseCode'] . ")"; ?>
                                </h5>
                                <p class="card-text">
                                    <?php echo $row['courseDescription']; ?>
                                </p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="courseIds[]" value="<?php echo $row['id']; ?>">
                                    <label class="form-check-label">
                                        Register for this course
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <p>No courses available for registration.</p>
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Register Selected Courses</button>
    </form>
</div>

<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <p>Stephazon University | World Domination through Education</p>
        <p>Contact Us: info@stephazonu.com</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
