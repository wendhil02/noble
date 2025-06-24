<?php
session_start();
include '../../connection/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? 0;
    $selected_type = $_POST['selected_type'] ?? '';
    $selected_variant = $_POST['selected_variant'] ?? '';

    if ($product_id == 0 || empty($selected_type) || empty($selected_variant)) {
        echo "<script>alert('Incomplete selection.'); window.history.back();</script>";
        exit;
    }

    // Get base product info
    $stmt = $conn->prepare("SELECT product_name, codename, quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    $product = $product_result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo "<script>alert('Product not found.'); window.history.back();</script>";
        exit;
    }

    // Get type_id
    $type_stmt = $conn->prepare("SELECT id FROM product_types WHERE product_id = ? AND type_name = ?");
    $type_stmt->bind_param("is", $product_id, $selected_type);
    $type_stmt->execute();
    $type_result = $type_stmt->get_result();
    $type_data = $type_result->fetch_assoc();
    $type_stmt->close();

    if (!$type_data) {
        echo "<script>alert('Type not found.'); window.history.back();</script>";
        exit;
    }

    $type_id = $type_data['id'];

    // Get variant data
    $variant_stmt = $conn->prepare("SELECT price, percent, discount, image FROM product_variants WHERE type_id = ? AND color = ?");
    $variant_stmt->bind_param("is", $type_id, $selected_variant);
    $variant_stmt->execute();
    $variant_result = $variant_stmt->get_result();
    $variant = $variant_result->fetch_assoc();
    $variant_stmt->close();

    if (!$variant) {
        echo "<script>alert('Variant not found.'); window.history.back();</script>";
        exit;
    }

    $variant_price = floatval($variant['price']);
    $percent = floatval($variant['percent']);
    $discount = floatval($variant['discount']);
    $bundle_quantity = intval($product['quantity']);

    // Calculation
    $price_with_markup = $variant_price + ($variant_price * $percent / 100);
    $price_with_discount = $price_with_markup - ($price_with_markup * $discount / 100);
    $final_price = $price_with_discount * $bundle_quantity;

    // Generate cart key
    $cart_key = $product_id . '_' . $selected_type . '_' . $selected_variant;

    // Initialize cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Encode image if exists
    $base64_image = '';
    if (!empty($variant['image'])) {
        $base64_image = base64_encode($variant['image']);
    }

    // Add or update cart
    if (!isset($_SESSION['cart'][$cart_key])) {
        $_SESSION['cart'][$cart_key] = [
            'product_id' => $product_id,
            'name' => $product['product_name'],
            'codename' => $product['codename'],
            'type' => $selected_type,
            'variant' => $selected_variant,
            'price' => $final_price,
            'quantity' => 1,
            'image' => $base64_image,
            'discount' => $discount
        ];
    } else {
        $_SESSION['cart'][$cart_key]['quantity'] += 1;
    }

    echo "<script>alert('Added to cart successfully!'); window.location.href='../product_view.php?id=$product_id';</script>";
    exit;
}
?>
