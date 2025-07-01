<?php
include '../../connection/connect.php';
require '../../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

// ✅ Ensure the qrcodes directory exists
$qrDir = __DIR__ . '/qrcodes/';
if (!is_dir($qrDir)) {
    mkdir($qrDir, 0777, true);
}

// ✅ Regenerate QR codes with PNG files
$fetchQuery = "SELECT id, codename FROM products";
$fetchResult = $conn->query($fetchQuery);

while ($row = $fetchResult->fetch_assoc()) {
    $productId = $row['id'];

    // Generate the link to view product
    $qrText = "http://localhost/noble/admin/qrcodeperproduct/view_product.php?id=$productId";

    // Generate QR
    $resultQR = Builder::create()
        ->writer(new PngWriter())
        ->data($qrText)
        ->size(300)
        ->margin(10)
        ->build();

    // Save the QR code as PNG file
    $qrFilename = "qr_" . $productId . ".png";
    $qrPath = $qrDir . $qrFilename;
    $resultQR->saveToFile($qrPath);

    // Store the path relative to this PHP file
    $relativePath = "qrcodes/" . $qrFilename;

    // Update the database
    $stmt = $conn->prepare("UPDATE products SET qr_code = ? WHERE id = ?");
    $stmt->bind_param("si", $relativePath, $productId);
    $stmt->execute();
}

// ✅ Fetch again for display
$displayQuery = "SELECT id, product_name, codename, quantity, price, qr_code FROM products ORDER BY product_name";
$displayResult = $conn->query($displayQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products with QR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
<?php include '../navbar/top.php'; ?>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-orange-600">Product List with QR Codes</h2>

        <?php if ($displayResult && $displayResult->num_rows > 0): ?>
            <table class="w-full table-auto border-collapse border border-gray-300 text-sm font-bold text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Codename</th>
                        <th class="border border-gray-300 px-4 py-2">Quantity</th>
                        <th class="border border-gray-300 px-4 py-2">Price</th>
                        <th class="border border-gray-300 px-4 py-2">QR Code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $displayResult->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($product['product_name']) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($product['codename']) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= $product['quantity'] ?></td>
                            <td class="border border-gray-300 px-4 py-2">₱<?= number_format($product['price'], 2) ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                             <?php if (!empty($product['qr_code']) && file_exists($product['qr_code'])): ?>
    <a href="http://localhost/noble/admin/qrcodeperproduct/view_product.php?id=<?= $product['id'] ?>" target="_blank">
        <img src="<?= htmlspecialchars($product['qr_code']) ?>" class="h-16 w-16 mx-auto mb-2 hover:scale-105 transition-transform" alt="QR Code" />
    </a>
    <a href="<?= htmlspecialchars($product['qr_code']) ?>" download
        class="inline-block mt-1 bg-green-600 hover:bg-green-700 text-white text-xs px-2 py-1 rounded">
        Download
    </a>
<?php else: ?>
    <span class="text-gray-400 italic">No QR</span>
<?php endif; ?>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500 text-center">No products found.</p>
        <?php endif; ?>
    </div>
</body>

</html>
