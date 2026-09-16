<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

include "../includes/db.php";

/* Delete student */

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM students WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: students.php");
    exit;
}

/* Get students */

$result = $conn->query(
    "SELECT id, name, email, phone, course, year, created_at
     FROM students
     ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Students</title>

    <link
        rel="stylesheet"
        href="../css/style.css">

</head>

<body>

<header class="navbar">

    <div class="logo">
        🏠 Admin Panel
    </div>

    <nav>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="students.php">
            Students
        </a>

        <a href="rooms.php">
            Rooms
        </a>

        <a href="bookings.php">
            Bookings
        </a>

        <a href="complaints.php">
            Complaints
        </a>

    </nav>

</header>

<div class="container">

    <h1>Student Management</h1>

    <br>

    <table>

        <tr>

            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Course</th>
            <th>Year</th>
            <th>Registered</th>
            <th>Action</th>

        </tr>

        <?php while ($student = $result->fetch_assoc()): ?>

        <tr>

            <td>
                <?= $student["id"] ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $student["name"]
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $student["email"]
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $student["phone"]
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $student["course"]
                ) ?>
            </td>

            <td>
                <?= $student["year"] ?>
            </td>

            <td>
                <?= $student["created_at"] ?>
            </td>

            <td>

                <a
                    class="btn"
                    href="students.php?delete=<?= $student["id"] ?>"
                    onclick="return confirm('Delete this student?')">

                    Delete

                </a>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<footer>

    <p>
        © 2026 College Hostel Management System
    </p>

</footer>

</body>
</html>