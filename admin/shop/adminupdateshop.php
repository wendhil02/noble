<?php
include '../../connection/connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $deleteId = (int) $_POST['delete_id'];
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $deleteId);
  $stmt->execute();
  $stmt->close();

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// ✅ Fetch all products
$products = $conn->query("SELECT id, product_name, codename, quantity, main_image FROM products ORDER BY product_name");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Select Product to Update</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <?php include '../navbar/top.php'; ?>

  <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow mt-5">
    <h2 class="text-2xl font-bold mb-6 text-orange-600">Select Product to Update</h2>

   <!-- 🔍 Search -->
  <input
    type="text"
    id="searchInput"
    placeholder="Search by name or codename..."
    class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
  />

  <!-- 🔽 Filter -->
  <select
    id="quantityFilter"
    class="w-full md:w-1/4 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
  >
    <option value="all">All Quantities</option>
    <option value="in-stock">In Stock</option>
    <option value="out-of-stock">Out of Stock</option>
  </select>

    <?php if ($products->num_rows > 0): ?>
      <div class="overflow-x-auto mt-5">
        <table class="w-full table-auto border-collapse border border-gray-300" id="productTable">
          <thead>
            <tr class="bg-gray-200">
              <th class="border border-gray-300 px-4 py-2 text-left">Image</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Product Name</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Codename</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Quantity</th>
              <th class="border border-gray-300 px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($product = $products->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50 product-row">
                <td class="border border-gray-300 px-4 py-2">
                  <?php if (!empty($product['main_image'])): ?>
                    <?php $imgData = base64_encode($product['main_image']); ?>
                    <img src="data:image/jpeg;base64,<?= $imgData ?>" class="h-16 w-16 object-contain rounded" />
                  <?php else: ?>
                    <span class="text-gray-400 italic">No image</span>
                  <?php endif; ?>
                </td>
                <td class="border border-gray-300 px-4 py-2 product-name"><?= htmlspecialchars($product['product_name']) ?></td>
                <td class="border border-gray-300 px-4 py-2 product-code"><?= htmlspecialchars($product['codename']) ?></td>
                <td class="border border-gray-300 px-4 py-2"><?= $product['quantity'] ?></td>
                <td class="border border-gray-300 px-4 py-2 text-center space-x-2">
                  <a href="update_product.php?id=<?= $product['id'] ?>" class="bg-orange-600 text-white px-3 py-1 rounded text-sm hover:bg-orange-700">
                    Update
                  </a>
                  <form action="" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    <input type="hidden" name="delete_id" value="<?= $product['id'] ?>">
                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                      Delete
                    </button>
                  </form>
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
      <div class="inline-flex gap-4">
        <a href="adminshop" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
          Back to Dashboard
        </a>
        <a href="newitem" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
          Update New Item
        </a>
      </div>
    </div>
  </div>

 <script>
  const searchInput = document.getElementById("searchInput");
  const quantityFilter = document.getElementById("quantityFilter");
  const rows = document.querySelectorAll(".product-row");

  function filterRows() {
    const searchTerm = searchInput.value.toLowerCase();
    const quantityValue = quantityFilter.value;

    rows.forEach(row => {
      const name = row.querySelector(".product-name").textContent.toLowerCase();
      const code = row.querySelector(".product-code").textContent.toLowerCase();
      const quantity = parseInt(row.querySelector(".product-qty").textContent);

      const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm);
      let matchesFilter = true;

      if (quantityValue === "in-stock") {
        matchesFilter = quantity > 0;
      } else if (quantityValue === "out-of-stock") {
        matchesFilter = quantity === 0;
      }

      row.style.display = matchesSearch && matchesFilter ? "" : "none";
    });
  }

  searchInput.addEventListener("input", filterRows);
  quantityFilter.addEventListener("change", filterRows);
</script>

</body>

</html>