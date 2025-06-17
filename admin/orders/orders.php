<?php
include '../../connection/connect.php';

// Fetch all orders
$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Orders</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
  <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold text-orange-700 mb-6">📦 All Orders</h2>

    <?php if ($orders->num_rows > 0): ?>
      <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="border rounded-lg p-4 mb-6 shadow-sm bg-gray-50">
          <div class="flex justify-between items-center mb-2">
            <div>
              <h3 class="text-lg font-semibold">Order #<?= $order['id'] ?></h3>
              <p class="text-sm text-gray-600">Customer: <?= htmlspecialchars($order['customer_name']) ?></p>
              <p class="text-sm text-gray-600">Email: <?= htmlspecialchars($order['email']) ?> | Mobile: <?= htmlspecialchars($order['mobile']) ?></p>
            </div>
            <div class="text-right">
              <p class="font-bold text-green-700 text-lg">₱<?= number_format($order['total'], 2) ?></p>
              <p class="text-sm text-gray-500"><?= $order['created_at'] ?></p>
            </div>
          </div>

          <!-- Order Items -->
          <?php
            $order_id = $order['id'];
            $items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
          ?>
          <div class="border-t pt-3">
            <table class="w-full text-sm">
              <thead class="bg-orange-100">
                <tr>
                  <th class="text-left py-1 px-2">Product</th>
                  <th class="text-left py-1 px-2">Codename</th>
                  <th class="text-left py-1 px-2">Type</th>
                  <th class="text-left py-1 px-2">Variant</th>
                  <th class="text-right py-1 px-2">Price</th>
                  <th class="text-right py-1 px-2">Qty</th>
                  <th class="text-right py-1 px-2">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($item = $items->fetch_assoc()): ?>
                  <tr class="border-b">
                    <td class="py-1 px-2"><?= htmlspecialchars($item['product_name']) ?></td>
                    <td class="py-1 px-2"><?= htmlspecialchars($item['codename']) ?></td>
                    <td class="py-1 px-2"><?= htmlspecialchars($item['type_name']) ?></td>
                    <td class="py-1 px-2"><?= htmlspecialchars($item['variant_color']) ?></td>
                    <td class="py-1 px-2 text-right">₱<?= number_format($item['price'], 2) ?></td>
                    <td class="py-1 px-2 text-right"><?= $item['quantity'] ?></td>
                    <td class="py-1 px-2 text-right font-semibold">₱<?= number_format($item['subtotal'], 2) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-gray-500">No orders found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
