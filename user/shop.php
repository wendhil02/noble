<?php
include '../connection/connect.php';

// Get selected categories from checkbox
$selected_categories = $_GET['category'] ?? [];

$filter_sql = '';
if (!empty($selected_categories)) {
  $escaped = array_map(function ($item) use ($conn) {
    return "'" . mysqli_real_escape_string($conn, $item) . "'";
  }, $selected_categories);

  $filter_sql = "WHERE codename IN (" . implode(',', $escaped) . ")";
}

$query = $conn->query("SELECT id, product_name, main_image FROM products $filter_sql");

$all_categories = ['furniture', 'material']; // Add more as needed
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Shop Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

  <?php include 'navbar/top.php'; ?>

  <div class="max-w-6xl mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-orange-600">Shop Products</h1>

    <!-- Filter Form -->
    <form method="GET" class="mb-6">
      <div class="flex flex-wrap gap-4">
        <?php foreach ($all_categories as $cat): ?>
          <label class="inline-flex items-center space-x-2">
            <input
              type="checkbox"
              name="category[]"
              value="<?= $cat ?>"
              <?= in_array($cat, $selected_categories) ? 'checked' : '' ?>
              class="form-checkbox h-5 w-5 text-orange-500 border-gray-300 rounded" />
            <span class="text-gray-800 capitalize"><?= htmlspecialchars($cat) ?></span>
          </label>
        <?php endforeach; ?>
        <button type="submit" class="ml-auto bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Filter</button>
      </div>
    </form>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
  <?php while ($row = $query->fetch_assoc()): ?>
    <?php
      $product_id = (int)$row['id'];
      $variant_count_query = "
        SELECT COUNT(*) as total 
        FROM product_variants pv
        JOIN product_types pt ON pv.type_id = pt.id
        WHERE pt.product_id = $product_id
      ";
      $variant_count_result = $conn->query($variant_count_query);
      $variant_count = $variant_count_result->fetch_assoc()['total'] ?? 0;
    ?>

    <a href="product_view.php?id=<?= $product_id ?>" class="bg-white rounded-xl shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 p-4 group">
      <?php if (!empty($row['main_image'])): ?>
        <div class="aspect-[4/5] bg-gray-50 rounded-lg overflow-hidden mb-3">
          <img
            src="data:image/jpeg;base64,<?= base64_encode($row['main_image']) ?>"
            class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
            alt="<?= htmlspecialchars($row['product_name']) ?>" />
        </div>
      <?php else: ?>
        <div class="w-full aspect-[4/5] flex items-center justify-center bg-gray-200 rounded-lg text-gray-500 text-sm mb-3">No Image</div>
      <?php endif; ?>

      <h2 class="text-base font-semibold text-center text-gray-800 break-words"><?= htmlspecialchars($row['product_name']) ?></h2>

      <!-- Variant Count -->
      <div class="mt-2 flex justify-center">
        <span class="text-xs px-3 py-1 bg-orange-500 text-white rounded-full font-medium">
          <?= $variant_count ?> Variant<?= $variant_count !== 1 ? 's' : '' ?>
        </span>
      </div>
    </a>
  <?php endwhile; ?>
</div>

  </div>

</body>

</html>