<?php
session_start();
include '../../connection/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// ✅ Confirm Order + Send Email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
  $orderId = (int) $_POST['confirm_order_id'];
  $discount_percent = floatval($_POST['discount'] ?? 0);
  $shipping_fee = floatval($_POST['shipping_fee'] ?? 0);

  // ✅ Fetch current total
  $order_result = $conn->query("SELECT * FROM orders WHERE id = $orderId");

  if ($order_result && $order_result->num_rows > 0) {
    $order_data = $order_result->fetch_assoc();

    $total = floatval($order_data['total']);
    $email = $order_data['email'];
    $name = $order_data['customer_name'];

    // ✅ Compute discount as percentage
    $discount_amount = ($total * $discount_percent) / 100;
    $grand_total = ($total - $discount_amount) + $shipping_fee;

    // ✅ Update order
    $conn->query("UPDATE orders 
              SET status = 'Confirmed', 
                  discount = $discount_percent, 
                  shipping_fee = $shipping_fee 
              WHERE id = $orderId");

// ✅ Insert into client_info if not yet saved
$client_check = $conn->query("SELECT id FROM client_info WHERE email = '$email'");
if ($client_check->num_rows == 0) {
  $client_name = $conn->real_escape_string($order_data['customer_name']);
  $client_email = $conn->real_escape_string($order_data['email']);
  $client_address = $conn->real_escape_string($order_data['address']);
  $client_contact = $conn->real_escape_string($order_data['mobile']);
  $client_zip = $conn->real_escape_string($order_data['zipcode']);
  $created_at = date('Y-m-d H:i:s');
  $client_status = 'Confirmed'; // ✅ this is the new status to insert

  $conn->query("INSERT INTO client_info 
    (name, address, email, contact, country, client_type, sex, status, created_at) 
    VALUES 
    ('$client_name', '$client_address', '$client_email', '$client_contact', '$client_zip', 'Customer', 'N/A', '$client_status', '$created_at')");
}



    // ✅ Send email
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'wendhil10@gmail.com';
      $mail->Password = 'tnjqjsuopqlwzoug';
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      $mail->setFrom('no-reply@yourdomain.com', 'NobleHome Orders');
      $mail->addAddress($email, $name);
      $mail->isHTML(true);
      $mail->Subject = "Order #$orderId Confirmed – Upload Payment";

      $mail->Body = "
        <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;'>
          <h2 style='color: #10b981;'>🧾 Order #$orderId Confirmed</h2>
          <p>Hi <strong>$name</strong>,</p>
          <p>We’ve confirmed your order. Please complete your payment:</p>
          <ul style='list-style:none; padding-left:0;'>
            <li><strong>Subtotal:</strong> ₱" . number_format($total, 2) . "</li>
            <li><strong>Discount:</strong> $discount_percent% (₱" . number_format($discount_amount, 2) . ")</li>
            <li><strong>Shipping Fee:</strong> ₱" . number_format($shipping_fee, 2) . "</li>
            <li><strong>Total:</strong> <span style='color:green;'>₱" . number_format($grand_total, 2) . "</span></li>
          </ul>
          <p style='margin-top:20px;'>
            <a href='http://localhost/noble/admin/orders/billing.php?order_id=$orderId' target='_blank'
              style='background:#10b981;color:white;padding:12px 20px;border-radius:8px;text-decoration:none;'>📤 Upload Proof of Payment</a>
          </p>
          <p>Thank you for shopping with <strong style='color:#ea580c;'>NobleHome</strong>! 🏠</p>
        </div>";

      $mail->send();
    } catch (Exception $e) {
      error_log("Mailer Error: {$mail->ErrorInfo}");
    }
  } else {
    // If order not found
    die("❌ Order not found.");
  }
}

// ✅ Fetch All Orders
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
          <div class="flex justify-between flex-wrap items-start gap-4 mb-2">
            <div class="flex-1 min-w-[250px]">
              <h3 class="text-lg font-semibold mb-1">Order #<?= $order['id'] ?></h3>
              <p class="text-sm text-gray-700"><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
              <p class="text-sm text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
              <p class="text-sm text-gray-700"><strong>Mobile:</strong> <?= htmlspecialchars($order['mobile']) ?></p>
              <p class="text-sm text-gray-700"><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['zipcode']) ?></p>
              <p class="text-sm text-gray-700"><strong>Status:</strong> <span class="text-blue-600 font-semibold"><?= $order['status'] ?? 'Pending' ?></span></p>
            </div>

            <!-- Totals + Confirm -->
            <div class="text-right min-w-[200px] space-y-2">
              <p class="text-green-700 font-bold">₱<?= number_format($order['total'], 2) ?> <span class="text-sm text-gray-400">(Subtotal)</span></p>
              <p class="text-sm">Discount: <?= number_format($order['discount'], 2) ?>%</p>
              <p class="text-sm">Shipping: ₱<?= number_format($order['shipping_fee'], 2) ?></p>
              <p class="font-bold text-lg text-orange-600">
                Total: ₱<?= number_format(($order['total'] - ($order['total'] * $order['discount'] / 100)) + $order['shipping_fee'], 2) ?>
              </p>
              <p class="text-sm text-gray-500"><?= date('F j, Y • h:i A', strtotime($order['created_at'])) ?></p>

              <?php if ($order['status'] !== 'Confirmed'): ?>
                <form method="POST" class="mt-2 space-y-2">
                  <input type="hidden" name="confirm_order_id" value="<?= $order['id'] ?>">
                  <input type="number" name="discount" placeholder="Discount (%)" step="0.01" class="w-full border px-2 py-1 text-sm rounded" />
                  <input type="number" name="shipping_fee" placeholder="Shipping Fee (₱)" step="0.01" class="w-full border px-2 py-1 text-sm rounded" />
                  <button type="submit" name="confirm_order" class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 text-sm w-full">
                    Confirm & Send Email
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </div>

          <!-- Order Items -->
          <?php
          $order_id = $order['id'];
          $items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
          ?>
          <div class="border-t pt-3 mt-3">
            <table class="w-full text-sm">
              <thead class="bg-orange-100 text-gray-700">
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
                  <tr class="border-b last:border-none">
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