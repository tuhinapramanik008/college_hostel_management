<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>College Hostel Management</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<header class="navbar">

    <div class="logo">
        🏠 College Hostel
    </div>

    <nav>

        <a href="index.php">Home</a>
        <a href="rooms.php">Rooms</a>

        <?php if (isset($_SESSION['student_id'])): ?>

            <a href="dashboard.php">Dashboard</a>
            <a href="complaints.php">Complaints</a>
            <a href="payment.php">Payments</a>
            <a href="logout.php">Logout</a>

        <?php else: ?>

            <a href="login.php">Login</a>
            <a href="register.php">Register</a>

        <?php endif; ?>

    </nav>

</header>