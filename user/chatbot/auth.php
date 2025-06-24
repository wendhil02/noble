<?php
session_start();
include '../../connection/connect.php';

// If session exists, user is logged in
if (isset($_SESSION['user_id'])) {
    return;
}

// If cookie exists, try to auto-login
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
    }
}
