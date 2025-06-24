<?php
session_start();
include '../../connection/connect.php';

// If no users exist, reset auto_increment to 1
$check = $conn->query("SELECT COUNT(*) AS total FROM users");
$row = $check->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("ALTER TABLE users AUTO_INCREMENT = 1");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already exists.";
    } else {
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            // ✅ Redirect to login (no auto-login)
            header("Location: login.php?registered=1");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">Create Your Account</h2>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-700">Name</label>
            <input type="text" name="name" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
        </div>

        <div>
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
        </div>

        <div>
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
        </div>

        <button type="submit"
            class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 transition">Register</button>

        <p class="text-sm text-center text-gray-600 mt-4">
            Already have an account?
            <a href="login.php" class="text-orange-500 hover:underline">Login here</a>
        </p>
    </form>
</div>

</body>
</html>
