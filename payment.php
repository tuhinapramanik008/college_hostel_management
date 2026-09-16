<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

include "includes/db.php";

$student_id = $_SESSION["student_id"];

$stmt = $conn->prepare(
    "SELECT amount,payment_date,payment_status
     FROM payments
     WHERE student_id = ?
     ORDER BY payment_date DESC"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<?php include "includes/header.php"; ?>

<div class="container">

    <h1>Payment History</h1>

    <br>

    <table>

        <tr>

            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>

        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>

            <td>
                ₹<?= number_format(
                    $row["amount"],
                    2
                ) ?>
            </td>

            <td>
                <?= $row["payment_date"] ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $row["payment_status"]
                ) ?>
            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php include "includes/footer.php"; ?>