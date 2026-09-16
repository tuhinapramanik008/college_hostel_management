<?php

include "includes/db.php";

$result = $conn->query(
    "SELECT *
     FROM rooms
     ORDER BY room_number"
);

?>

<?php include "includes/header.php"; ?>

<div class="container">

    <h1>Available Hostel Rooms</h1>

    <br>

    <table>

        <tr>

            <th>Room</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Occupied</th>
            <th>Monthly Fee</th>
            <th>Status</th>
            <th>Action</th>

        </tr>

        <?php while ($room = $result->fetch_assoc()): ?>

        <tr>

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
                    $room["price"],
                    2
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $room["status"]
                ) ?>
            </td>

            <td>

                <?php if ($room["status"] == "Available"): ?>

                    <?php if (isset($_SESSION["student_id"])): ?>

                        <a
                            class="btn"
                            href="booking.php?room_id=<?= $room["id"] ?>">
                            Apply
                        </a>

                    <?php else: ?>

                        <a
                            class="btn"
                            href="login.php">
                            Login
                        </a>

                    <?php endif; ?>

                <?php else: ?>

                    Full

                <?php endif; ?>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php include "includes/footer.php"; ?>