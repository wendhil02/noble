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

// Check if table is empty
$result = $conn->query("SELECT COUNT(*) as total FROM product_types");
$row = $result->fetch_assoc();

if ($row['total'] == 0) {
    // Reset auto_increment to 1
    $conn->query("ALTER TABLE product_types AUTO_INCREMENT = 1");
}


$result = $conn->query("SELECT COUNT(*) as total FROM product_variants");
$row = $result->fetch_assoc();

if ($row['total'] == 0) {
    // Reset auto_increment to 1
    $conn->query("ALTER TABLE product_variants AUTO_INCREMENT = 1");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $codename = $_POST['codename'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Read main image as BLOB
    $main_image_data = null;
    if ($_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $main_image_data = file_get_contents($_FILES['main_image']['tmp_name']);
    }

    // Insert product
    $stmt = $conn->prepare("INSERT INTO products (product_name, codename, quantity, price, main_image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssids", $product_name, $codename, $quantity, $price, $main_image_data);
    $stmt->send_long_data(4, $main_image_data); // 5th parameter (index 4)
    $stmt->execute();
    $product_id = $stmt->insert_id;

    // Loop through types
    foreach ($_POST['type_name'] as $i => $type_name) {
        $type_image_data = null;
        if ($_FILES['type_image']['error'][$i] === UPLOAD_ERR_OK) {
            $type_image_data = file_get_contents($_FILES['type_image']['tmp_name'][$i]);
        }

        $stmt_type = $conn->prepare("INSERT INTO product_types (product_id, type_name, type_image) VALUES (?, ?, ?)");
        $stmt_type->bind_param("iss", $product_id, $type_name, $type_image_data);
        $stmt_type->send_long_data(2, $type_image_data); // 3rd param = index 2
        $stmt_type->execute();
        $type_id = $stmt_type->insert_id;

        // Variants
        if (!empty($_POST["variant_color"][$i])) {
            foreach ($_POST["variant_color"][$i] as $j => $variant_color) {
                $variant_size = $_POST["variant_size"][$i][$j];
                $variant_price = $_POST["variant_price"][$i][$j];
                $variant_percent = $_POST["variant_percent"][$i][$j];

                $variant_image_data = null;
                if (isset($_FILES["variant_image"]["error"][$i][$j]) && $_FILES["variant_image"]["error"][$i][$j] === UPLOAD_ERR_OK) {
                    $variant_image_data = file_get_contents($_FILES["variant_image"]["tmp_name"][$i][$j]);
                }

                $stmt_var = $conn->prepare("INSERT INTO product_variants (type_id, color, size, price, percent, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_var->bind_param("issdds", $type_id, $variant_color, $variant_size, $variant_price, $variant_percent, $variant_image_data);
                $stmt_var->send_long_data(5, $variant_image_data); // 6th param = index 5
                $stmt_var->execute();
            }
        }
    }

    echo "<script>alert('Product uploaded successfully!'); window.location.href='adminshop.php';</script>";
    exit;
} else {
    echo "Invalid request.";
}
?>
