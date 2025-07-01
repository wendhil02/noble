<?php
session_start();
include '../../connection/connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$product_id = $_POST['product_id'] ?? null;
if (!$product_id) {
    echo "Missing product ID.";
    exit;
}

// Main product fields
$product_name = $_POST['product_name'];
$codename = $_POST['codename'];
$quantity = $_POST['quantity'];
$description = $_POST['description'] ?? '';
$main_image = null;

if (!empty($_FILES['main_image']['tmp_name'])) {
    $main_image = file_get_contents($_FILES['main_image']['tmp_name']);
}

// ✅ FIXED: Correct main image update handling
if ($main_image) {
    $stmt = $conn->prepare("UPDATE products SET product_name=?, codename=?, quantity=?, description=?, main_image=? WHERE id=?");
    $stmt->bind_param("ssissi", $product_name, $codename, $quantity, $description, $main_image, $product_id);
} else {
    $stmt = $conn->prepare("UPDATE products SET product_name=?, codename=?, quantity=?, description=? WHERE id=?");
    $stmt->bind_param("ssisi", $product_name, $codename, $quantity, $description, $product_id);
}
$stmt->execute();

// Rest of your code remains the same...
// DELETE TYPES
if (!empty($_POST['delete_type'])) {
    foreach ($_POST['delete_type'] as $type_id) {
        $type_id = intval($type_id);
        $conn->query("DELETE FROM product_variants WHERE type_id = $type_id");
        $conn->query("DELETE FROM product_types WHERE id = $type_id");
    }
}

// DELETE VARIANTS
if (!empty($_POST['delete_variant'])) {
    foreach ($_POST['delete_variant'] as $typeIndex => $variantIds) {
        foreach ($variantIds as $variantId) {
            $variantId = intval($variantId);
            $conn->query("DELETE FROM product_variants WHERE id = $variantId");
        }
    }
}

// UPDATE OR INSERT TYPES & VARIANTS
foreach ($_POST['type_id'] as $i => $type_id) {
    $type_name = $_POST['type_name'][$i];
    $type_image = null;

    if (!empty($_FILES['type_image']['tmp_name'][$i])) {
        $type_image = file_get_contents($_FILES['type_image']['tmp_name'][$i]);
    }

    // Add or update type
    if ($type_id === 'new') {
        if ($type_image) {
            $stmt = $conn->prepare("INSERT INTO product_types (product_id, type_name, type_image) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $product_id, $type_name, $type_image);
        } else {
            $stmt = $conn->prepare("INSERT INTO product_types (product_id, type_name) VALUES (?, ?)");
            $stmt->bind_param("is", $product_id, $type_name);
        }
        $stmt->execute();
        $type_id = $stmt->insert_id;
    } else {
        $type_id = intval($type_id);
        if ($type_image) {
            $stmt = $conn->prepare("UPDATE product_types SET type_name=?, type_image=? WHERE id=?");
            $stmt->bind_param("ssi", $type_name, $type_image, $type_id);
        } else {
            $stmt = $conn->prepare("UPDATE product_types SET type_name=? WHERE id=?");
            $stmt->bind_param("si", $type_name, $type_id);
        }
        $stmt->execute();
    }

    // Variants
    if (!empty($_POST['variant_color'][$i])) {
        $variant_colors = $_POST['variant_color'][$i];
        foreach ($variant_colors as $j => $color) {
            $variant_id = $_POST['variant_id'][$i][$j];
            $size = $_POST['variant_size'][$i][$j];
            $price = $_POST['variant_price'][$i][$j];
            $percent = $_POST['variant_percent'][$i][$j] ?? 0;
            $discount = $_POST['variant_discount'][$i][$j] ?? 0;
            $namevariant = $_POST['variant_namevariant'][$i][$j] ?? '';
            $variant_image = null;

            //  FIXED: Correct file input handling for variant images
            if (isset($_FILES['variant_image']['tmp_name'][$i][$j]) && 
                !empty($_FILES['variant_image']['tmp_name'][$i][$j]) &&
                $_FILES['variant_image']['error'][$i][$j] === UPLOAD_ERR_OK) {
                $variant_image = file_get_contents($_FILES['variant_image']['tmp_name'][$i][$j]);
            }

            // New variant
            if ($variant_id === "new") {
                if ($variant_image) {
                    $stmt = $conn->prepare("INSERT INTO product_variants (type_id, color, size, price, percent, discount, namevariant, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issdddss", $type_id, $color, $size, $price, $percent, $discount, $namevariant, $variant_image);
                } else {
                    $stmt = $conn->prepare("INSERT INTO product_variants (type_id, color, size, price, percent, discount, namevariant) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issddds", $type_id, $color, $size, $price, $percent, $discount, $namevariant);
                }
                $stmt->execute();
            } else {
                // Update existing variant
                $variant_id = intval($variant_id);
                if ($variant_image) {
                    $stmt = $conn->prepare("UPDATE product_variants SET color=?, size=?, price=?, percent=?, discount=?, namevariant=?, image=? WHERE id=?");
                    $stmt->bind_param("ssdddssi", $color, $size, $price, $percent, $discount, $namevariant, $variant_image, $variant_id);
                } else {
                    $stmt = $conn->prepare("UPDATE product_variants SET color=?, size=?, price=?, percent=?, discount=?, namevariant=? WHERE id=?");
                    $stmt->bind_param("ssdddsi", $color, $size, $price, $percent, $discount, $namevariant, $variant_id);
                }
                $stmt->execute();
            }
        }
    }
}


echo "<script>alert('Product updated successfully!'); window.location.href = 'adminshop.php';</script>";
?>