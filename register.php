
<?php

include "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data safely
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $department = trim($_POST["department"] ?? "");
    $year = intval($_POST["year"] ?? 0);
    $password = $_POST["password"] ?? "";

    // Validate required fields
    if (
        empty($name) ||
        empty($email) ||
        empty($password)
    ) {

        $message = "Please fill all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        // Hash password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        try {

            // Prepare INSERT query
            $stmt = $conn->prepare(
                "INSERT INTO students
                (name, email, phone, department, year, password)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            // Bind parameters
            $stmt->bind_param(
                "ssssis",
                $name,
                $email,
                $phone,
                $department,
                $year,
                $hashedPassword
            );

            // Execute query
            if ($stmt->execute()) {

                $message =
                    "Registration successful! You can now login.";

            }

            $stmt->close();

        } catch (mysqli_sql_exception $e) {

            // Duplicate email
            if ($e->getCode() == 1062) {

                $message =
                    "This email is already registered. Please use another email.";

            } else {

                $message =
                    "Registration failed. Please try again.";

                // For debugging, you can temporarily uncomment:
                // $message = $e->getMessage();
            }
        }
    }
}

?>

<?php include "includes/header.php"; ?>

<div class="form-box">

    <h2>Student Registration</h2>

    <?php if (!empty($message)): ?>

        <p style="margin-bottom:15px;">
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>


    <form method="POST">

        <!-- Name -->
        <div class="form-group">

            <label>Name</label>

            <input
                type="text"
                name="name"
                required>

        </div>


        <!-- Email -->
        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                required>

        </div>


        <!-- Phone -->
        <div class="form-group">

            <label>Phone</label>

            <input
                type="text"
                name="phone">

        </div>


        <!-- Department -->
        <div class="form-group">

            <label>Department</label>

            <input
                type="text"
                name="department">

        </div>


        <!-- Year -->
        <div class="form-group">

            <label>Year</label>

            <select name="year">

                <option value="1">1st Year</option>

                <option value="2">2nd Year</option>

                <option value="3">3rd Year</option>

                <option value="4">4th Year</option>

            </select>

        </div>


        <!-- Password -->
        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                required
                minlength="6">

        </div>


        <!-- Register Button -->
        <button
            class="btn"
            type="submit">

            Register

        </button>

    </form>

</div>

<?php include "includes/footer.php"; ?>

