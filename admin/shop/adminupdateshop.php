<?php 
include '../../connection/connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all products
$products = $conn->query("SELECT id, product_name, codename, quantity FROM products ORDER BY product_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Select Product to Update</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-orange-600">Select Product to Update</h2>

    <?php if ($products->num_rows > 0): ?>
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-200">
              <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Product Name</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Codename</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Quantity</th>
              <th class="border border-gray-300 px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($product = $products->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50">
                <td class="border border-gray-300 px-4 py-2"><?= $product['id'] ?></td>
                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($product['product_name']) ?></td>
                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($product['codename']) ?></td>
                <td class="border border-gray-300 px-4 py-2"><?= $product['quantity'] ?></td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                  <a href="update_product.php?id=<?= $product['id'] ?>" 
                     class="bg-orange-600 text-white px-3 py-1 rounded text-sm hover:bg-orange-700">
                    Update
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="text-center py-8">
        <p class="text-gray-600 text-lg">No products found.</p>
        <a href="../add_product/" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Add New Product
        </a>
      </div>
    <?php endif; ?>

    <div class="mt-6 text-center">
      <a href="adminshop" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        Back to Dashboard
      </a>
    </div>
  </div>
</body>
</html>