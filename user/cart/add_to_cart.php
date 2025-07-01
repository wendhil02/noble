<?php
session_start();
include '../../connection/connect.php';

// Set content type to JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product_id = $_POST['product_id'] ?? 0;
        $selected_type = $_POST['selected_type'] ?? '';
        $selected_variant = $_POST['selected_variant'] ?? '';

        if ($product_id == 0 || empty($selected_type) || empty($selected_variant)) {
            throw new Exception('Incomplete selection.');
        }

        // Get base product info
        $stmt = $conn->prepare("SELECT product_name, codename, quantity FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product_result = $stmt->get_result();
        $product = $product_result->fetch_assoc();
        $stmt->close();

        if (!$product) {
            throw new Exception('Product not found.');
        }

        // Get type_id
        $type_stmt = $conn->prepare("SELECT id FROM product_types WHERE product_id = ? AND type_name = ?");
        $type_stmt->bind_param("is", $product_id, $selected_type);
        $type_stmt->execute();
        $type_result = $type_stmt->get_result();
        $type_data = $type_result->fetch_assoc();
        $type_stmt->close();

        if (!$type_data) {
            throw new Exception('Type not found.');
        }

        $type_id = $type_data['id'];

        // Get variant data (updated to include namevariant)
        $variant_stmt = $conn->prepare("SELECT price, percent, discount, image, namevariant FROM product_variants WHERE type_id = ? AND color = ?");
        $variant_stmt->bind_param("is", $type_id, $selected_variant);
        $variant_stmt->execute();
        $variant_result = $variant_stmt->get_result();
        $variant = $variant_result->fetch_assoc();
        $variant_stmt->close();

        if (!$variant) {
            throw new Exception('Variant not found.');
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
                'namevariant' => $variant['namevariant'] ?? '',
                'price' => $final_price,
                'quantity' => 1,
                'image' => $base64_image,
                'discount' => $discount
            ];
        } else {
            $_SESSION['cart'][$cart_key]['quantity'] += 1;
        }

        // Get updated cart count
        $cart_count = getTotalCartItems();

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Added to cart successfully!',
            'cart_count' => $cart_count
        ]);

    } catch (Exception $e) {
        // Return error response
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'cart_count' => getTotalCartItems()
        ]);
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed',
        'cart_count' => 0
    ]);
}

// Function to get total cart items
function getTotalCartItems() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'];
    }
    
    return $total;
}
?>