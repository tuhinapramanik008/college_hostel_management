<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

include "../includes/db.php";


/*
|--------------------------------------------------------------------------
| APPROVE BOOKING
|--------------------------------------------------------------------------
*/

if (
    isset($_GET["approve"])
) {

    $booking_id =
        intval($_GET["approve"]);

    $conn->begin_transaction();

    try {

        /* Get booking information */

        $stmt = $conn->prepare(
            "SELECT room_id, status
             FROM bookings
             WHERE id = ?
             FOR UPDATE"
        );

        $stmt->bind_param(
            "i",
            $booking_id
        );

        $stmt->execute();

        $booking =
            $stmt->get_result()->fetch_assoc();


        if (!$booking) {

            throw new Exception(
                "Booking not found."
            );

        }


        if ($booking["status"] != "Pending") {

            throw new Exception(
                "Booking has already been processed."
            );

        }


        /* Check room availability */

        $stmt = $conn->prepare(
            "SELECT capacity, occupied
             FROM rooms
             WHERE id = ?
             FOR UPDATE"
        );

        $stmt->bind_param(
            "i",
            $booking["room_id"]
        );

        $stmt->execute();

        $room =
            $stmt->get_result()->fetch_assoc();


        if (!$room) {

            throw new Exception(
                "Room not found."
            );

        }


        if (
            $room["occupied"] >=
            $room["capacity"]
        ) {

            throw new Exception(
                "Room is already full."
            );

        }


        /* Approve booking */

        $stmt = $conn->prepare(
            "UPDATE bookings
             SET status = 'Approved'
             WHERE id = ?"
        );

        $stmt->bind_param(
            "i",
            $booking_id
        );

        $stmt->execute();


        /* Increase room occupancy */

        $stmt = $conn->prepare(
            "UPDATE rooms
             SET occupied = occupied + 1,
                 status =
                 CASE
                    WHEN occupied + 1 >= capacity
                    THEN 'Full'
                    ELSE 'Available'
                 END
             WHERE id = ?"
        );

        $stmt->bind_param(
            "i",
            $booking["room_id"]
        );

        $stmt->execute();


        $conn->commit();

    } catch (Exception $e) {

        $conn->rollback();

        die(
            "Error: " .
            htmlspecialchars($e->getMessage())
        );
    }


    header("Location: bookings.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| REJECT BOOKING
|--------------------------------------------------------------------------
*/

if (
    isset($_GET["reject"])
) {

    $booking_id =
        intval($_GET["reject"]);

    $stmt = $conn->prepare(
        "UPDATE bookings
         SET status = 'Rejected'
         WHERE id = ?
         AND status = 'Pending'"
    );

    $stmt->bind_param(
        "i",
        $booking_id
    );

    $stmt->execute();

    header("Location: bookings.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| GET BOOKINGS
|--------------------------------------------------------------------------
*/

$result = $conn->query(
    "SELECT
        b.id,
        b.booking_date,
        b.status,

        s.name AS student_name,
        s.email AS student_email,

        r.room_number,
        r.room_type,
        r.fee

     FROM bookings b

     INNER JOIN students s
        ON b.student_id = s.id

     INNER JOIN rooms r
        ON b.room_id = r.id

     ORDER BY b.id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Bookings</title>

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
        Booking Management
    </h1>

    <br>


    <table>

        <tr>

            <th>ID</th>

            <th>Student</th>

            <th>Email</th>

            <th>Room</th>

            <th>Type</th>

            <th>Fee</th>

            <th>Date</th>

            <th>Status</th>

            <th>Action</th>

        </tr>


        <?php while (
            $booking =
            $result->fetch_assoc()
        ): ?>

        <tr>

            <td>
                <?= $booking["id"] ?>
            </td>


            <td>

                <?= htmlspecialchars(
                    $booking["student_name"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $booking["student_email"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $booking["room_number"]
                ) ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $booking["room_type"]
                ) ?>

            </td>


            <td>

                ₹<?= number_format(
                    $booking["fee"],
                    2
                ) ?>

            </td>


            <td>

                <?= $booking["booking_date"] ?>

            </td>


            <td>

                <?= htmlspecialchars(
                    $booking["status"]
                ) ?>

            </td>


            <td>

                <?php if (
                    $booking["status"] ==
                    "Pending"
                ): ?>

                    <a
                        class="btn"
                        href="bookings.php?approve=<?= $booking["id"] ?>"
                        onclick="return confirm('Approve this booking?')">

                        Approve

                    </a>

                    <br><br>

                    <a
                        class="btn"
                        href="bookings.php?reject=<?= $booking["id"] ?>"
                        onclick="return confirm('Reject this booking?')">

                        Reject

                    </a>

                <?php else: ?>

                    Processed

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