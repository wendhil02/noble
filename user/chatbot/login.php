<?php
session_start();
include '../../connection/connect.php';

// If no users exist, reset auto_increment to 1
$check = $conn->query("SELECT COUNT(*) AS total FROM users");
$row = $check->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("ALTER TABLE users AUTO_INCREMENT = 1");
}


// ✅ Auto-login via remember_token
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: messenger.php");
        exit;
    }
}

// ✅ If already logged in, go to messenger
if (isset($_SESSION['user_id'])) {
    header("Location: messenger.php");
    exit;
}

// ✅ Handle login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $remember = isset($_POST['remember']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();

        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // ✅ Remember me (store token in cookie + DB)
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie("remember_token", $token, time() + (30 * 24 * 60 * 60), "/"); // 30 days

                $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->bind_param("si", $token, $user['id']);
                $stmt->execute();
            }

            header("Location: messenger.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">User Login</h2>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" placeholder="Email"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" required>
        </div>

        <div>
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" placeholder="Password"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" required>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit"
            class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 transition">Login</button>

        <p class="text-sm text-center text-gray-600 mt-4">
            No account yet?
            <a href="register.php" class="text-orange-500 hover:underline">Register here</a>
        </p>
    </form>
</div>

</body>
</html>

