<?php
session_start();
include '../../connection/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

// ✅ Safely handle confirmation POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'], $_POST['confirm_order_id'])) {
  $orderId = (int) $_POST['confirm_order_id'];
  if ($orderId <= 0) {
    die("❌ Invalid order ID.");
  }

  $discount_percent = floatval($_POST['discount'] ?? 0);
  $shipping_fee = floatval($_POST['shipping_fee'] ?? 0);

  $order_result = $conn->query("SELECT * FROM orders WHERE id = $orderId");
  if ($order_result && $order_result->num_rows > 0) {
    $order_data = $order_result->fetch_assoc();
    $total = floatval($order_data['total']);
    $email = $order_data['email'];
    $name = $order_data['customer_name'];
    $discount_amount = ($total * $discount_percent) / 100;
    $grand_total = ($total - $discount_amount) + $shipping_fee;

    // ✅ Update order to 'Ongoing'
    $conn->query("UPDATE orders SET status='Ongoing', discount=$discount_percent, shipping_fee=$shipping_fee WHERE id=$orderId");

    // ✅ Insert or reuse client info with reference number
    $client_check = $conn->query("SELECT id, reference_no FROM client_info WHERE email = '$email'");
    if ($client_check->num_rows == 0) {
      $today = date('Ymd');
      $count_result = $conn->query("SELECT COUNT(*) AS total FROM client_info WHERE DATE(created_at) = CURDATE()");
      $count_row = $count_result->fetch_assoc();
      $reference_no = 'NOBLE-' . $today . '-' . str_pad($count_row['total'] + 1, 4, '0', STR_PAD_LEFT);

      $client_name = $conn->real_escape_string($order_data['customer_name']);
      $client_email = $conn->real_escape_string($order_data['email']);
      $client_address = $conn->real_escape_string($order_data['address']);
      $client_contact = $conn->real_escape_string($order_data['mobile']);
      $client_zip = $conn->real_escape_string($order_data['zipcode']);
      $created_at = date('Y-m-d H:i:s');
      $client_status = 'Ongoing';

      $conn->query("INSERT INTO client_info 
        (name, address, email, contact, country, client_type, sex, status, created_at, reference_no) 
        VALUES 
        ('$client_name', '$client_address', '$client_email', '$client_contact', '$client_zip', 'Customer', 'N/A', '$client_status', '$created_at', '$reference_no')");
    } else {
      $client = $client_check->fetch_assoc();
      $reference_no = $client['reference_no'];
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
      $mail->Subject = "Order #$orderId Ongoing – Upload Payment";
      $mail->Body = "
        <div style='font-family: Arial, sans-serif; padding: 20px;'>
          <h2 style='color: #10b981;'>🧾 Order #$orderId Ongoing</h2>
          <p>Hi <strong>$name</strong>,</p>
          <p>Your order has been confirmed and is now ongoing. Please proceed with the payment:</p>
          <ul>
            <li><strong>Subtotal:</strong> ₱" . number_format($total, 2) . "</li>
            <li><strong>Discount:</strong> $discount_percent% (₱" . number_format($discount_amount, 2) . ")</li>
            <li><strong>Shipping Fee:</strong> ₱" . number_format($shipping_fee, 2) . "</li>
            <li><strong>Total:</strong> <span style='color:green;'>₱" . number_format($grand_total, 2) . "</span></li>
          </ul>
          <p>
            <a href='http://localhost/noble/admin/orders/billing.php?order_id=$orderId' target='_blank'
              style='background:#10b981;color:white;padding:10px 18px;border-radius:6px;text-decoration:none;'>View Billing</a>
          </p>
          <p>Thank you for choosing <strong style='color:#ea580c;'>NobleHome</strong>!</p>
        </div>";
      $mail->send();
    } catch (Exception $e) {
      error_log("Mailer Error: {$mail->ErrorInfo}");
    }
  } else {
    die("❌ Order not found.");
  }
}

// ✅ Fetch orders based on new status
$pendingOrders = $conn->query("SELECT * FROM orders WHERE status != 'Ongoing' ORDER BY created_at DESC");
$confirmedOrders = $conn->query("SELECT * FROM orders WHERE status = 'Ongoing' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Orders</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<?php include '../navbar/top.php'; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

  <!-- Pending Orders -->
  <div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-xl font-bold text-orange-700 mb-4">Pending Orders</h2>
    <?php if ($pendingOrders->num_rows > 0): ?>
      <?php while ($order = $pendingOrders->fetch_assoc()): ?>
        <?php renderOrderCard($order, $conn); ?>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-gray-500">No pending orders found.</p>
    <?php endif; ?>
  </div>

  <!-- Ongoing Orders -->
  <div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-xl font-bold text-green-700 mb-4">Ongoing Orders</h2>
    <?php if ($confirmedOrders->num_rows > 0): ?>
      <?php while ($order = $confirmedOrders->fetch_assoc()): ?>
        <?php renderOrderCard($order, $conn); ?>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-gray-500">No ongoing orders found.</p>
    <?php endif; ?>
  </div>

</div>
</body>
</html>

<?php
function renderOrderCard($order, $conn) {
  $order_id = $order['id'];
  $items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");

  $total = $order['total'];
  $discount = $order['discount'];
  $shipping = $order['shipping_fee'];
  $final_total = ($total - ($total * $discount / 100)) + $shipping;

  echo '<div class="border border-gray-200 rounded-md p-4 mb-4 bg-gray-50">';
  echo '<div class="mb-2">';
  echo '<h3 class="font-bold text-lg">Order #' . $order_id . '</h3>';
  echo '<p class="text-sm"><strong>Customer:</strong> ' . htmlspecialchars($order['customer_name']) . '</p>';
  echo '<p class="text-sm"><strong>Email:</strong> ' . htmlspecialchars($order['email']) . '</p>';
  echo '<p class="text-sm"><strong>Mobile:</strong> ' . htmlspecialchars($order['mobile']) . '</p>';
  echo '<p class="text-sm"><strong>Address:</strong> ' . htmlspecialchars($order['address']) . ', ' . htmlspecialchars($order['zipcode']) . '</p>';
  echo '<p class="text-sm"><strong>Status:</strong> <span class="text-blue-600 font-semibold">' . $order['status'] . '</span></p>';
  echo '</div>';

  echo '<div class="text-sm text-right">';
  echo '<p class="text-green-700 font-semibold">₱' . number_format($total, 2) . ' <span class="text-gray-400">(Subtotal)</span></p>';
  echo '<p>Discount: ' . number_format($discount, 2) . '%</p>';
  echo '<p>Shipping: ₱' . number_format($shipping, 2) . '</p>';
  echo '<p class="font-bold text-orange-600">Total: ₱' . number_format($final_total, 2) . '</p>';
  echo '<p class="text-gray-400">' . date('F j, Y • h:i A', strtotime($order['created_at'])) . '</p>';
  echo '</div>';

  if ($order['status'] !== 'Ongoing') {
    echo '<form method="POST" class="mt-2 space-y-1">';
    echo '<input type="hidden" name="confirm_order_id" value="' . $order_id . '">';
    echo '<input type="number" name="discount" placeholder="Discount (%)" step="0.01" class="w-full border px-2 py-1 text-sm rounded" />';
    echo '<input type="number" name="shipping_fee" placeholder="Shipping Fee (₱)" step="0.01" class="w-full border px-2 py-1 text-sm rounded" />';
    echo '<button type="submit" name="confirm_order" class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 text-sm w-full">Confirm & Send Email</button>';
    echo '</form>';
  }

  echo '<div class="mt-4 border-t pt-2">';
  echo '<table class="w-full text-xs">';
  echo '<thead class="bg-orange-100">';
  echo '<tr><th class="text-left px-2 py-1">Product</th><th class="text-left px-2 py-1">Codename</th><th class="text-left px-2 py-1">Type</th><th class="text-left px-2 py-1">Variant</th><th class="text-right px-2 py-1">Price</th><th class="text-right px-2 py-1">Qty</th><th class="text-right px-2 py-1">Subtotal</th></tr>';
  echo '</thead><tbody>';
  while ($item = $items->fetch_assoc()) {
    echo '<tr class="border-b">';
    echo '<td class="px-2 py-1">' . htmlspecialchars($item['product_name']) . '</td>';
    echo '<td class="px-2 py-1">' . htmlspecialchars($item['codename']) . '</td>';
    echo '<td class="px-2 py-1">' . htmlspecialchars($item['type_name']) . '</td>';
    echo '<td class="px-2 py-1">' . htmlspecialchars($item['variant_color']) . '</td>';
    echo '<td class="px-2 py-1 text-right">₱' . number_format($item['price'], 2) . '</td>';
    echo '<td class="px-2 py-1 text-right">' . $item['quantity'] . '</td>';
    echo '<td class="px-2 py-1 text-right font-semibold">₱' . number_format($item['subtotal'], 2) . '</td>';
    echo '</tr>';
  }
  echo '</tbody></table>';
  echo '</div></div>';
}
?>
