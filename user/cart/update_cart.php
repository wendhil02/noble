<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantities'] as $key => $qty) {
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] = max(1, (int)$qty);
        }
    }
}

header('Location: ../cart_view.php');
exit;
