<?php

session_start();

include "../includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id,username,password
         FROM admins
         WHERE username = ?"
    );

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $admin = $result->fetch_assoc();

        if ($password === $admin["password"]) {

            $_SESSION["admin_id"] =
                $admin["id"];

            $_SESSION["admin_username"] =
                $admin["username"];

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Invalid password.";

        }

    } else {

        $message = "Admin not found.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Login</title>

    <link
        rel="stylesheet"
        href="../css/style.css">

</head>

<body>

<div class="form-box">

    <h2>Admin Login</h2>

    <?php if ($message): ?>

        <p><?= htmlspecialchars($message) ?></p>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label>Username</label>

            <input
                type="text"
                name="username"
                required>

        </div>

        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                required>

        </div>

        <button class="btn">
            Login
        </button>

    </form>

    <br>

    <p>
        Demo Login:
        <strong>admin / admin123</strong>
    </p>

</div>

</body>

</html>