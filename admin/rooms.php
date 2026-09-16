<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

include "../includes/db.php";

$message = "";

/* Add room */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $room_number = trim($_POST["room_number"]);
    $room_type = trim($_POST["room_type"]);
    $capacity = intval($_POST["capacity"]);
    $fee = floatval($_POST["fee"]);

    if (
        empty($room_number) ||
        empty($room_type) ||
        $capacity <= 0 ||
        $fee <= 0
    ) {

        $message = "Please enter valid room details.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO rooms
            (room_number, room_type, capacity, occupied, fee, status)
            VALUES (?, ?, ?, 0, ?, 'Available')"
        );

        $stmt->bind_param(
            "ssid",
            $room_number,
            $room_type,
            $capacity,
            $fee
        );

        if ($stmt->execute()) {

            $message = "Room added successfully.";

        } else {

            $message =
                "Room number already exists or an error occurred.";

        }

        $stmt->close();
    }
}

/* Delete room */

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM rooms WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: rooms.php");
    exit;
}

/* Get rooms */

$result = $conn->query(
    "SELECT *
     FROM rooms
     ORDER BY room_number"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Rooms</title>

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

    <h1>Room Management</h1>

    <br>

    <?php if ($message): ?>

        <div class="card">

            <p>
                <?= htmlspecialchars($message) ?>
            </p>

        </div>

        <br>

    <?php endif; ?>


    <!-- Add Room Form -->

    <div class="form-box">

        <h2>Add New Room</h2>

        <form method="POST">

            <div class="form-group">

                <label>
                    Room Number
                </label>

                <input
                    type="text"
                    name="room_number"
                    placeholder="Example: 201"
                    required>

            </div>


            <div class="form-group">

                <label>
                    Room Type
                </label>

                <select
                    name="room_type"
                    required>

                    <option value="">
                        Select Room Type
                    </option>

                    <option value="Single">
                        Single
                    </option>

                    <option value="Double">
                        Double
                    </option>

                    <option value="Triple">
                        Triple
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Capacity
                </label>

                <input
                    type="number"
                    name="capacity"
                    min="1"
                    required>

            </div>


            <div class="form-group">

                <label>
                    Monthly Fee
                </label>

                <input
                    type="number"
                    name="fee"
                    min="1"
                    step="0.01"
                    required>

            </div>


            <button
                type="submit"
                class="btn">

                Add Room

            </button>

        </form>

    </div>


    <h2>All Rooms</h2>

    <br>

    <table>

        <tr>

            <th>ID</th>
            <th>Room No.</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Occupied</th>
            <th>Fee</th>
            <th>Status</th>
            <th>Action</th>

        </tr>


        <?php while ($room = $result->fetch_assoc()): ?>

        <tr>

            <td>
                <?= $room["id"] ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $room["room_number"]
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $room["room_type"]
                ) ?>
            </td>

            <td>
                <?= $room["capacity"] ?>
            </td>

            <td>
                <?= $room["occupied"] ?>
            </td>

            <td>
                ₹<?= number_format(
                    $room["fee"],
                    2
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $room["status"]
                ) ?>
            </td>

            <td>

                <a
                    class="btn"
                    href="rooms.php?delete=<?= $room["id"] ?>"
                    onclick="return confirm('Delete this room?')">

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