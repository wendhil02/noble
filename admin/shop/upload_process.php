<?php
include '../../connection/connect.php';

// Reset AUTO_INCREMENT if tables are empty
$tables = ['products', 'product_types', 'product_variants'];
foreach ($tables as $table) {
    $result = $conn->query("SELECT COUNT(*) as total FROM $table");
    $row = $result->fetch_assoc();
    if ($row['total'] == 0) {
        $conn->query("ALTER TABLE $table AUTO_INCREMENT = 1");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $codename = $_POST['codename'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';

    // Main image (BLOB) - Fixed validation
    $main_image_data = null;
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $main_image_data = file_get_contents($_FILES['main_image']['tmp_name']);
    }

    // Insert product with LONGBLOB for images
    $stmt = $conn->prepare("INSERT INTO products (product_name, codename, quantity, price, description, main_image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidss", $product_name, $codename, $quantity, $price, $description, $main_image_data);
    $stmt->execute();
    $product_id = $stmt->insert_id;
    $stmt->close();

    // Loop through types - Fixed array handling
    if (isset($_POST['type_name']) && is_array($_POST['type_name'])) {
        foreach ($_POST['type_name'] as $i => $type_name) {
            $type_image_data = null;
            
            // Fixed type image handling
            if (isset($_FILES['type_image']['error'][$i]) && $_FILES['type_image']['error'][$i] === UPLOAD_ERR_OK) {
                $type_image_data = file_get_contents($_FILES['type_image']['tmp_name'][$i]);
            }

            $stmt_type = $conn->prepare("INSERT INTO product_types (product_id, type_name, type_image) VALUES (?, ?, ?)");
            $stmt_type->bind_param("iss", $product_id, $type_name, $type_image_data);
            $stmt_type->execute();
            $type_id = $stmt_type->insert_id;
            $stmt_type->close();

            // Loop through variants - Fixed field name mismatch
            if (isset($_POST["variant_color"][$i]) && is_array($_POST["variant_color"][$i])) {
                foreach ($_POST["variant_color"][$i] as $j => $variant_color) {
                    $variant_size = $_POST["variant_size"][$i][$j] ?? '';
                    $variant_price = $_POST["variant_price"][$i][$j] ?? 0;
                    $variant_percent = $_POST["variant_percent"][$i][$j] ?? 0;
                    $variant_discount = $_POST["variant_discount"][$i][$j] ?? 0;
                    // Fixed field name - was 'namevariant' but form sends 'variant_namevariant'
                    $namevariant = $_POST["variant_namevariant"][$i][$j] ?? '';

                    $variant_image_data = null;
                    if (isset($_FILES["variant_image"]["error"][$i][$j]) && 
                        $_FILES["variant_image"]["error"][$i][$j] === UPLOAD_ERR_OK) {
                        $variant_image_data = file_get_contents($_FILES["variant_image"]["tmp_name"][$i][$j]);
                    }

                    $stmt_var = $conn->prepare("INSERT INTO product_variants (type_id, color, size, price, percent, discount, namevariant, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt_var->bind_param("issdddss", $type_id, $variant_color, $variant_size, $variant_price, $variant_percent, $variant_discount, $namevariant, $variant_image_data);
                    $stmt_var->execute();
                    $stmt_var->close();
                }
            }
        }
    }

    echo "<script>alert('Product uploaded successfully!'); window.location.href='adminshop.php';</script>";
    exit;
} else {
    echo "Invalid request.";
}
?>