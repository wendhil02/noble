<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">

  <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-orange-700 mb-6">🛒 Your Cart</h2>

    <?php if (empty($cart)): ?>
      <p class="text-gray-600">Your cart is empty.</p>
      <a href="../shop.php" class="text-orange-600 underline mt-4 inline-block">← Continue Shopping</a>
    <?php else: ?>
      <form action="update_cart.php" method="POST">
        <table class="w-full table-auto text-left mb-6">
          <thead>
            <tr class="text-sm text-gray-600 border-b">
              <th>Image</th>
              <th>Product</th>
              <th>Codename</th>
              <th>Type</th>
              <th>Color</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $key => $item): 
              $basePrice = floatval($item['price']);
              $percent = floatval($item['percent'] ?? 0);
              $priceWithMarkup = $basePrice + ($basePrice * $percent / 100);
              $subtotal = $priceWithMarkup * $item['quantity'];
              $total_price += $subtotal;
            ?>
              <tr class="border-b text-sm">
                <td class="py-2">
                 <img src="data:image/jpeg;base64,<?= $item['image'] ?: '' ?>" alt="Product Image" class="w-16 h-16 object-contain rounded" />

                </td>
                <td class="font-semibold"><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['codename']) ?></td>
                <td><?= htmlspecialchars($item['type']) ?></td>
                <td><?= htmlspecialchars($item['variant']) ?></td>
                <td>
                  <input type="number" name="quantities[<?= $key ?>]" value="<?= $item['quantity'] ?>" min="1" class="w-16 border rounded px-2 py-1 text-center" />
                </td>
                <td>₱<?= number_format($priceWithMarkup, 2) ?></td>
                <td class="text-green-600 font-medium">₱<?= number_format($subtotal, 2) ?></td>
                <td>
                  <a href="remove_from_cart.php?key=<?= urlencode($key) ?>" class="text-red-600 hover:underline">Remove</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="flex justify-between items-center">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Cart</button>
          <div class="text-xl font-bold text-orange-700">
            Total: ₱<?= number_format($total_price, 2) ?>
          </div>
        </div>
      </form>

      <div class="mt-6 text-right">
        <a href="checkout.php" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">Proceed to Checkout</a>
      </div>
      <div class="mt-4">
        <a href="../shop.php" class="text-blue-600 underline">← Continue Shopping</a>
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
