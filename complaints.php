<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

include "includes/db.php";

$student_id = $_SESSION["student_id"];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $subject = trim($_POST["subject"]);
    $description = trim($_POST["description"]);

    if ($subject == "" || $description == "") {

        $message = "Please fill all fields.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO complaints
            (student_id,subject,description)
            VALUES (?,?,?)"
        );

        $stmt->bind_param(
            "iss",
            $student_id,
            $subject,
            $description
        );

        if ($stmt->execute()) {
            $message =
                "Complaint submitted successfully.";
        }
    }
}

$stmt = $conn->prepare(
    "SELECT subject,description,status,created_at
     FROM complaints
     WHERE student_id = ?
     ORDER BY id DESC"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<?php include "includes/header.php"; ?>

<div class="container">

    <div class="form-box">

        <h2>Submit Complaint</h2>

        <?php if ($message): ?>

            <p><?= htmlspecialchars($message) ?></p>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label>Subject</label>

                <input
                    type="text"
                    name="subject"
                    required>

            </div>

            <div class="form-group">

                <label>Description</label>

                <textarea
                    name="description"
                    required></textarea>

            </div>

            <button class="btn">
                Submit Complaint
            </button>

        </form>

    </div>

    <h2>My Complaints</h2>

    <br>

    <table>

        <tr>
            <th>Subject</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>

            <td>
                <?= htmlspecialchars(
                    $row["subject"]
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $row["description"]
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $row["status"]
                ) ?>
            </td>

            <td>
                <?= $row["created_at"] ?>
            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php include "includes/footer.php"; ?>