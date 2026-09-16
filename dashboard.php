
<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

include "includes/db.php";

$student_id = $_SESSION["student_id"];

// Get student information
$stmt = $conn->prepare(
    "SELECT name, email, phone, department, year
     FROM students
     WHERE id = ?"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$student = $stmt->get_result()->fetch_assoc();


// Get latest room booking
$booking_stmt = $conn->prepare(
    "SELECT b.status, r.room_number, r.room_type, r.price
     FROM bookings b
     JOIN rooms r ON b.room_id = r.id
     WHERE b.student_id = ?
     ORDER BY b.id DESC
     LIMIT 1"
);

$booking_stmt->bind_param("i", $student_id);
$booking_stmt->execute();

$booking = $booking_stmt->get_result()->fetch_assoc();

?>

<?php include "includes/header.php"; ?>

<div class="container">

    <h1>
        Welcome, <?= htmlspecialchars($student["name"]) ?>
    </h1>

    <br>

    <div class="dashboard-cards">

        <!-- Room -->
        <div class="dashboard-card">

            <h2>🏠</h2>

            <h3>Room</h3>

            <p>
                <?= $booking
                    ? htmlspecialchars($booking["room_number"])
                    : "Not Assigned"
                ?>
            </p>

        </div>


        <!-- Booking Status -->
        <div class="dashboard-card">

            <h2>📋</h2>

            <h3>Booking Status</h3>

            <p>
                <?= $booking
                    ? htmlspecialchars($booking["status"])
                    : "No Booking"
                ?>
            </p>

        </div>


        <!-- Department -->
        <div class="dashboard-card">

            <h2>🎓</h2>

            <h3>Department</h3>

            <p>
                <?= htmlspecialchars(
                    $student["department"]
                ) ?>
            </p>

        </div>


        <!-- Year -->
        <div class="dashboard-card">

            <h2>📅</h2>

            <h3>Year</h3>

            <p>
                <?= htmlspecialchars(
                    $student["year"]
                ) ?>
            </p>

        </div>

    </div>


    <!-- Student Information -->
    <div class="card">

        <h2>Student Information</h2>

        <br>

        <p>
            <strong>Email:</strong>
            <?= htmlspecialchars($student["email"]) ?>
        </p>

        <p>
            <strong>Phone:</strong>
            <?= htmlspecialchars($student["phone"]) ?>
        </p>

        <p>
            <strong>Department:</strong>
            <?= htmlspecialchars($student["department"]) ?>
        </p>

        <p>
            <strong>Year:</strong>
            <?= htmlspecialchars($student["year"]) ?>
        </p>

        <br>

        <a class="btn" href="rooms.php">
            Book a Room
        </a>

        <a class="btn" href="complaints.php">
            Submit Complaint
        </a>

    </div>

</div>

<?php include "includes/footer.php"; ?>

