```php
<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

include "includes/db.php";

$student_id = $_SESSION["student_id"];


// Check room_id
if (!isset($_GET["room_id"])) {
    header("Location: rooms.php");
    exit;
}

$room_id = intval($_GET["room_id"]);


// Get room details
$stmt = $conn->prepare(
    "SELECT *
     FROM rooms
     WHERE id = ?"
);

$stmt->bind_param("i", $room_id);
$stmt->execute();

$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    die("Room not found.");
}

$message = "";


// Handle booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if student already has an active booking
    $check = $conn->prepare(
        "SELECT id
         FROM bookings
         WHERE student_id = ?
         AND status IN ('Pending', 'Approved')"
    );

    $check->bind_param("i", $student_id);
    $check->execute();

    $existing = $check->get_result();


    if ($existing->num_rows > 0) {

        $message = "You already have an active booking.";

    }

    // Check room capacity
    elseif ($room["occupied"] >= $room["capacity"]) {

        $message = "This room is full.";

    }

    else {

        $booking_date = date("Y-m-d");


        // Insert booking
        $insert = $conn->prepare(
            "INSERT INTO bookings
            (student_id, room_id, booking_date, status)
            VALUES (?, ?, ?, 'Pending')"
        );

        $insert->bind_param(
            "iis",
            $student_id,
            $room_id,
            $booking_date
        );


        if ($insert->execute()) {

            $message =
                "Room application submitted successfully.";

        } else {

            $message =
                "Booking failed. Please try again.";
        }

        $insert->close();
    }

    $check->close();
}

?>

<?php include "includes/header.php"; ?>

<div class="form-box">

    <h2>Room Booking</h2>


    <!-- Message -->
    <?php if ($message): ?>

        <p style="margin-bottom: 15px;">
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>


    <!-- Room Details -->
    <div class="card">

        <h3>
            Room
            <?= htmlspecialchars($room["room_number"]) ?>
        </h3>


        <p>
            <strong>Type:</strong>
            <?= htmlspecialchars($room["room_type"]) ?>
        </p>


        <p>
            <strong>Capacity:</strong>
            <?= htmlspecialchars($room["capacity"]) ?>
        </p>


        <p>
            <strong>Occupied:</strong>
            <?= htmlspecialchars($room["occupied"]) ?>
        </p>


        <p>
            <strong>Available:</strong>
            <?= max(
                0,
                $room["capacity"] - $room["occupied"]
            ) ?>
        </p>


        <p>
            <strong>Monthly Fee:</strong>
            ₹<?= number_format(
                $room["price"],
                2
            ) ?>
        </p>

    </div>


    <br>


    <!-- Booking Form -->
    <form method="POST">

        <button
            type="submit"
            class="btn">

            Confirm Application

        </button>

    </form>


    <br>


    <a
        href="rooms.php"
        class="btn">

        Back to Rooms

    </a>

</div>

<?php include "includes/footer.php"; ?>
```
