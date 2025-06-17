<?php
include '../../connection/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $lvl = $_POST['lvl'];

    // Secure password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into nobleaccount
    $stmt = $conn->prepare("INSERT INTO nobleaccount (email, password, lvl) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashed_password, $lvl);

    if ($stmt->execute()) {
        echo "Account registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
