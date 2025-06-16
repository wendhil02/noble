<?php
include '../../connection/connect.php';

if (isset($_POST['submit'])) {
    $variantId = $_POST['variant_id'];
    $images = $_FILES['images'];
    $descriptions = $_POST['descriptions'];

    for ($i = 0; $i < count($images['name']); $i++) {
        $tmpName = $images['tmp_name'][$i];
        $desc = addslashes($descriptions[$i]);

        if ($tmpName) {
            $imgData = addslashes(file_get_contents($tmpName));
            $query = "INSERT INTO images (variant_id, image_data, description) VALUES ('$variantId', '$imgData', '$desc')";
            mysqli_query($conn, $query);
        }
    }

    header("Location: variant_select.php?upload=success");
    exit();
}
?>
