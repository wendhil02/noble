<?php
include '../connection/connect.php';

$query = $conn->query("SELECT id, product_name, main_image FROM products");
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

  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-orange-600">Shop Products</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php while ($row = $query->fetch_assoc()): ?>
        <a href="product_view.php?id=<?= (int)$row['id'] ?>" class="bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300 p-4 group">
          <?php if (!empty($row['main_image'])): ?>
            <img 
              src="data:image/jpeg;base64,<?= base64_encode($row['main_image']) ?>" 
              class="w-full h-48 object-contain rounded group-hover:scale-105 transition-transform duration-300" 
              alt="<?= htmlspecialchars($row['product_name']) ?>" 
            />
          <?php else: ?>
            <div class="w-full h-48 flex items-center justify-center bg-gray-200 rounded text-gray-500">No Image</div>
          <?php endif; ?>
          <h2 class="mt-3 text-lg font-semibold text-gray-800 text-center"><?= htmlspecialchars($row['product_name']) ?></h2>
        </a>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>

