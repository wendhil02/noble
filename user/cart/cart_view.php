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
<body class="bg-gray-100 font-sans">

<div class="max-w-7xl mx-auto px-4 py-4">
  <nav class="text-sm text-gray-600">
    <a href="../index" class="hover:text-orange-600">Home</a>
    <span class="mx-2">/</span>
    <a href="../shop.php" class="hover:text-orange-600">Shop</a>
    <span class="mx-2">/</span>
    <span class="text-orange-600 font-medium">Cart</span>
  </nav>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
  <div class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-3xl font-bold text-orange-700 mb-6 flex items-center gap-2">🛒 Your Cart</h2>

    <?php if (empty($cart)): ?>
      <p class="text-gray-600 text-lg">Your cart is currently empty.</p>
      <a href="../shop.php" class="inline-block mt-4 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition">Continue Shopping</a>
    <?php else: ?>
      <form action="update_cart.php" method="POST">
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
              <tr>
                <th class="py-3 px-4">Image</th>
                <th class="py-3 px-4">Product</th>
                <th class="py-3 px-4">Codename</th>
                <th class="py-3 px-4">Type</th>
                <th class="py-3 px-4">Color</th>
                <th class="py-3 px-4">Qty</th>
                <th class="py-3 px-4">Unit Price</th>
                <th class="py-3 px-4">Subtotal</th>
                <th class="py-3 px-4">Remove</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php foreach ($cart as $key => $item):
                $basePrice = floatval($item['price']);
                $percent = floatval($item['percent'] ?? 0);
                $priceWithMarkup = $basePrice + ($basePrice * $percent / 100);
                $subtotal = $priceWithMarkup * $item['quantity'];
                $total_price += $subtotal;
              ?>
                <tr>
                  <td class="py-3 px-4">
                    <img src="data:image/jpeg;base64,<?= $item['image'] ?>" alt="Product" class="w-16 h-16 object-contain rounded">
                  </td>
                  <td class="py-3 px-4 font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></td>
                  <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($item['codename']) ?></td>
                  <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($item['type']) ?></td>
                  <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($item['variant']) ?></td>
                  <td class="py-3 px-4">
                    <input type="number" name="quantities[<?= $key ?>]" value="<?= $item['quantity'] ?>" min="1" class="w-16 border-gray-300 border rounded px-2 py-1 text-center shadow-sm">
                  </td>
                  <td class="py-3 px-4 text-orange-600 font-medium">₱<?= number_format($priceWithMarkup, 2) ?></td>
                  <td class="py-3 px-4 text-green-600 font-bold">₱<?= number_format($subtotal, 2) ?></td>
                  <td class="py-3 px-4">
                    <a href="remove_from_cart.php?key=<?= urlencode($key) ?>" class="text-red-500 hover:text-red-700 hover:underline">Remove</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-6 flex justify-between items-center flex-wrap gap-4">
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow transition">Update Cart</button>
          <div class="text-xl font-bold text-orange-700">
            Total: ₱<?= number_format($total_price, 2) ?>
          </div>
        </div>
      </form>

      <div class="mt-6 flex flex-wrap gap-3 justify-end">
        <a href="../shop.php" class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded transition">Continue Shopping</a>
        <a href="checkout.php" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded transition">Proceed to Checkout</a>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
