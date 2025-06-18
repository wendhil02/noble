<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../connection/connect.php';

// Check if table is empty
$result = $conn->query("SELECT COUNT(*) as total FROM products");
$row = $result->fetch_assoc();

if ($row['total'] == 0) {
    // Reset auto_increment to 1
    $conn->query("ALTER TABLE products AUTO_INCREMENT = 1");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $codename = $_POST['codename'];
    $quantity = $_POST['quantity'];

    // Handle main image (only update if uploaded)
    if (!empty($_FILES['main_image']['tmp_name'])) {
        $main_image_data = file_get_contents($_FILES['main_image']['tmp_name']);
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, codename = ?, quantity = ?, main_image = ? WHERE id = ?");
        $stmt->bind_param("ssbsi", $product_name, $codename, $quantity, $main_image_data, $product_id);
        $stmt->send_long_data(3, $main_image_data); // index 3 is main_image
    } else {
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, codename = ?, quantity = ? WHERE id = ?");
        $stmt->bind_param("sssi", $product_name, $codename, $quantity, $product_id);
    }
    $stmt->execute();

    // Loop through each type
    foreach ($_POST['type_id'] as $i => $type_id) {
        $type_name = $_POST['type_name'][$i];

        // Update type (with image if exists)
        if (!empty($_FILES['type_image']['tmp_name'][$i])) {
            $type_image_data = file_get_contents($_FILES['type_image']['tmp_name'][$i]);
            $stmt = $conn->prepare("UPDATE product_types SET type_name = ?, type_image = ? WHERE id = ?");
            $stmt->bind_param("sbi", $type_name, $type_image_data, $type_id);
            $stmt->send_long_data(1, $type_image_data);
        } else {
            $stmt = $conn->prepare("UPDATE product_types SET type_name = ? WHERE id = ?");
            $stmt->bind_param("si", $type_name, $type_id);
        }
        $stmt->execute();

        // Handle variants per type
        if (!empty($_POST['variant_color'][$i])) {
            foreach ($_POST['variant_color'][$i] as $j => $variant_color) {
                $variant_size = $_POST['variant_size'][$i][$j];
                $variant_price = $_POST['variant_price'][$i][$j];
                $variant_percent = isset($_POST['variant_percent'][$i][$j]) && $_POST['variant_percent'][$i][$j] !== '' 
                    ? floatval($_POST['variant_percent'][$i][$j]) 
                    : 0;

                $variant_id = $_POST['variant_id'][$i][$j] ?? null;
                $has_image = isset($_FILES['variant_image']['tmp_name'][$i][$j]) && $_FILES['variant_image']['error'][$i][$j] === UPLOAD_ERR_OK;

                if ($variant_id === 'new') {
                    // Insert new variant
                    if ($has_image) {
                        $variant_image_data = file_get_contents($_FILES['variant_image']['tmp_name'][$i][$j]);
                        $stmt = $conn->prepare("INSERT INTO product_variants (type_id, color, size, price, percent, image) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("issdds", $type_id, $variant_color, $variant_size, $variant_price, $variant_percent, $variant_image_data);
                        $stmt->send_long_data(5, $variant_image_data);
                    } else {
                        $stmt = $conn->prepare("INSERT INTO product_variants (type_id, color, size, price, percent) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("issdd", $type_id, $variant_color, $variant_size, $variant_price, $variant_percent);
                    }
                    $stmt->execute();
                } else {
                    // Update existing variant
                    if ($has_image) {
                        $variant_image_data = file_get_contents($_FILES['variant_image']['tmp_name'][$i][$j]);
                        $stmt = $conn->prepare("UPDATE product_variants SET color = ?, size = ?, price = ?, percent = ?, image = ? WHERE id = ?");
                        $stmt->bind_param("ssddsi", $variant_color, $variant_size, $variant_price, $variant_percent, $variant_image_data, $variant_id);
                        $stmt->send_long_data(4, $variant_image_data);
                    } else {
                        $stmt = $conn->prepare("UPDATE product_variants SET color = ?, size = ?, price = ?, percent = ? WHERE id = ?");
                        $stmt->bind_param("ssdsi", $variant_color, $variant_size, $variant_price, $variant_percent, $variant_id);
                    }
                    $stmt->execute();
                }
            }
        }
    }

    echo "<script>alert('Product updated successfully!'); window.location.href='adminshop.php';</script>";
    exit;
} else {
    echo "Invalid request.";
}
?>
