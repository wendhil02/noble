<?php
session_start();
include '../../connection/connect.php';

$cart = $_SESSION['cart'] ?? [];
$total_price = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['customer_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    if ($name && $email && $mobile && !empty($cart)) {
        // Save to orders table
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, email, mobile, total) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $name, $email, $mobile, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Save to order_items table
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, codename, type_name, variant_color, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->bind_param(
                "issssdii",
                $order_id,
                $item['name'],
                $item['codename'],
                $item['type'],
                $item['variant'],
                $item['price'],
                $item['quantity'],
                $subtotal
            );
            $stmt->execute();
        }
        $stmt->close();

        $_SESSION['cart'] = []; // Clear cart
        echo "<script>alert('Order placed successfully!'); window.location.href='cart_view.php';</script>";
        exit;
    } else {
        $error = "Please complete all fields and ensure your cart is not empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6 font-sans">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold text-orange-700 mb-6">🧾 Checkout</h2>

        <?php if (isset($error)): ?>
            <p class="text-red-600 mb-4"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <!-- Full Name -->
            <div>
                <label class="block font-medium">Full Name</label>
                <input type="text" name="customer_name" required class="w-full border px-4 py-2 rounded" />
            </div>

            <!-- Email -->
            <div>
                <label class="block font-medium">Email</label>
                <input type="email" name="email" required class="w-full border px-4 py-2 rounded" />
            </div>

            <!-- Mobile -->
            <div>
                <label class="block font-medium">Mobile</label>
                <input type="text" name="mobile" required class="w-full border px-4 py-2 rounded" />
            </div>

            <!-- Address -->
            <div>
                <label class="block font-medium">Address</label>
                <textarea name="address" required class="w-full border px-4 py-2 rounded resize-none" rows="2"></textarea>
            </div>

            <!-- Zip Code -->
            <div>
                <label class="block font-medium">Zip Code</label>
                <input type="text" name="zipcode" required class="w-full border px-4 py-2 rounded" />
            </div>

            <!-- Order Summary -->
            <h3 class="text-xl font-semibold mt-6 mb-2">Order Summary</h3>
            <ul class="divide-y">
                <?php foreach ($cart as $item): ?>
                    <li class="py-2 text-sm">
                        <?= htmlspecialchars($item['name']) ?> (<?= $item['type'] ?> / <?= $item['variant'] ?>)
                        <span class="float-right">₱<?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="text-right text-xl mt-4 font-bold text-green-700">
                Total: ₱<?= number_format($total_price, 2) ?>
            </div>

            <!-- Submit -->
            <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 mt-6">
                Place Order
            </button>

            <div class="mt-6">
                <a href="cart_view.php" class="text-blue-600 hover:underline">← Back to Cart</a>
            </div>
        </form>

    </div>

</body>

</html>