<?php

session_start();

include "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id,name,password
         FROM students
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $student = $result->fetch_assoc();

        if (
            password_verify(
                $password,
                $student["password"]
            )
        ) {

            $_SESSION["student_id"] =
                $student["id"];

            $_SESSION["student_name"] =
                $student["name"];

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "Student account not found.";

    }

    $stmt->close();
}

?>

<?php include "includes/header.php"; ?>

<div class="form-box">

    <h2>Student Login</h2>

    <?php if ($message): ?>

        <p><?= htmlspecialchars($message) ?></p>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                required>

        </div>

        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                required>

        </div>

        <button
            class="btn"
            type="submit">

            Login

        </button>

    </form>

</div>

<?php include "includes/footer.php"; ?>