<?php
session_start();
$key = $_GET['key'] ?? '';
if ($key && isset($_SESSION['cart'][$key])) {
    unset($_SESSION['cart'][$key]);
}
header('Location: ../cart_view.php');
exit;
