<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

include "../includes/db.php";


/*
|--------------------------------------------------------------------------
| UPDATE COMPLAINT STATUS
|--------------------------------------------------------------------------
*/

if (
    isset($_GET["id"]) &&
    isset($_GET["status"])
) {

    $id =
        intval($_GET["id"]);

    $status =
        $_GET["status"];


    $allowed_statuses = [
        "Pending",
        "In Progress",
        "Resolved"
    ];


    if (
        in_array(
            $status,
            $allowed_statuses
        )
    ) {

        $stmt = $conn->prepare(
            "UPDATE complaints
             SET status = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $status,
            $id
        );

        $stmt->execute();

    }


    header("Location: complaints.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| GET COMPLAINTS
|--------------------------------------------------------------------------
*/

$result = $conn->query(
    "SELECT
        c.id,
        c.subject,
        c.description,
        c.status,
        c.created_at,

        s.name AS student_name,
        s.email AS student_email,
        s.phone AS student_phone

     FROM complaints c

     INNER JOIN students s
        ON c.student_id = s.id

     ORDER BY c.id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Complaints</title>

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

    <h1>
        Complaint Management
    </h1>

    <br>


    <table>

        <tr>

            <th>ID</th>

            <th>Student</th>

            <th>Email</th>

            <th>Phone</th>

            <th>Subject</th>

            <th>Description</th>

            <th>Status</th>

            <th>Date</th>

            <th>Action</th>

        </tr>


        <?php while (
            $complaint =
            $result->fetch_assoc()
        ): ?>

        <tr>

            <td>

                <?= $complaint["id"] ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $complaint["student_name"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $complaint["student_email"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $complaint["student_phone"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $complaint["subject"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $complaint["description"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $complaint["status"]
                ) ?>

            </td>


            <td>

                <?= $complaint["created_at"] ?>

            </td>


            <td>


                <?php if (
                    $complaint["status"] ==
                    "Pending"
                ): ?>

                    <a
                        class="btn"
                        href="complaints.php?id=<?= $complaint["id"] ?>&status=In%20Progress">

                        In Progress

                    </a>

                    <br><br>

                <?php endif; ?>


                <?php if (
                    $complaint["status"] !=
                    "Resolved"
                ): ?>

                    <a
                        class="btn"
                        href="complaints.php?id=<?= $complaint["id"] ?>&status=Resolved"
                        onclick="return confirm('Mark this complaint as resolved?')">

                        Resolve

                    </a>

                <?php else: ?>

                    Resolved

                <?php endif; ?>


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