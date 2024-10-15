<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db_connection.php';

// Fetch the user's first name
$firstName = $_SESSION['user_firstName'];
$userId = $_SESSION['user_id'];

// Fetch enrolled courses
$sql = "SELECT c.id, c.courseName, c.courseCode
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
    <title>Dashboard - Stephazon University</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            height: 100%;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .jumbotron {
            background-color: #f8f9fa;
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<div class="jumbotron text-center">
    <h1>Welcome to Your Throne, <?php echo $firstName; ?>!</h1>
    <p>Your path to world domination continues. Manage your courses and take charge of your education.</p>
</div>

<div class="container mt-4">
    <h2>Your Registered Courses</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card mb-4 h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $row['courseName'] . " (" . $row['courseCode'] . ")"; ?>
                            </h5>
                            <form action="process_delete_course.php" method="POST" style="display:inline;">
                                <input type="hidden" name="courseId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm float-right">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No courses registered yet. Start building your empire now.</p>
    <?php endif; ?>

    <br>
    <a href="register_courses.php" class="btn btn-primary btn-lg btn-block">Conquer New Territories: Register for New Courses</a>
    <br><br>
    <a href="logout.php" class="btn btn-danger btn-lg btn-block">Abandon Your Throne: Logout</a>
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
