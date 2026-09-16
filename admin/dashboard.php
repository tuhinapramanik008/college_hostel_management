<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

include "../includes/db.php";

$students =
    $conn->query(
        "SELECT COUNT(*) AS total
         FROM students"
    )->fetch_assoc()["total"];

$rooms =
    $conn->query(
        "SELECT COUNT(*) AS total
         FROM rooms"
    )->fetch_assoc()["total"];

$bookings =
    $conn->query(
        "SELECT COUNT(*) AS total
         FROM bookings
         WHERE status='Pending'"
    )->fetch_assoc()["total"];

$complaints =
    $conn->query(
        "SELECT COUNT(*) AS total
         FROM complaints
         WHERE status='Pending'"
    )->fetch_assoc()["total"];

?>

<?php include "../includes/header.php"; ?>

<div class="container">

    <h1>Admin Dashboard</h1>

    <br>

    <div class="dashboard-cards">

        <div class="dashboard-card">

            <h2><?= $students ?></h2>

            <h3>Students</h3>

        </div>

        <div class="dashboard-card">

            <h2><?= $rooms ?></h2>

            <h3>Rooms</h3>

        </div>

        <div class="dashboard-card">

            <h2><?= $bookings ?></h2>

            <h3>Pending Bookings</h3>

        </div>

        <div class="dashboard-card">

            <h2><?= $complaints ?></h2>

            <h3>Pending Complaints</h3>

        </div>

    </div>

    <div class="cards">

        <div class="card">

            <h3>Students</h3>

            <a
                href="students.php"
                class="btn">

                Manage Students

            </a>

        </div>

        <div class="card">

            <h3>Rooms</h3>

            <a
                href="rooms.php"
                class="btn">

                Manage Rooms

            </a>

        </div>

        <div class="card">

            <h3>Bookings</h3>

            <a
                href="bookings.php"
                class="btn">

                Manage Bookings

            </a>

        </div>

        <div class="card">

            <h3>Complaints</h3>

            <a
                href="complaints.php"
                class="btn">

                Manage Complaints

            </a>

        </div>

    </div>

</div>

<?php include "../includes/footer.php"; ?>