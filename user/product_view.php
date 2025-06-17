<?php
include '../connection/connect.php';

$product_id = $_GET['id'] ?? 0;

// Get product info
$product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();
$types = $conn->query("SELECT * FROM product_types WHERE product_id = $product_id");

$type_variant_map = [];
$type_names = [];
$type_ids = [];

foreach ($types as $type) {
    $type_name = $type['type_name'];
    $type_id = $type['id'];
    $type_names[] = $type_name;
    $type_ids[] = $type_id;

    $variants = $conn->query("SELECT * FROM product_variants WHERE type_id = $type_id");
    $type_variant_map[$type_name] = $variants->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($product['product_name']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .selected-ring {
      outline: 2px solid #fb923c;
      outline-offset: 2px;
    }
  </style>
</head>
<body class="bg-white p-6 font-sans">
  <div class="max-w-4xl mx-auto">
    <div class="grid md:grid-cols-2 gap-6">
      <!-- Product Image -->
      <img src="data:image/jpeg;base64,<?= base64_encode($product['main_image']) ?>" class="w-full rounded-xl shadow" />

      <!-- Product Info -->
      <div>
        <h2 class="text-2xl font-bold text-orange-700"><?= htmlspecialchars($product['product_name']) ?></h2>
        <p class="text-sm text-gray-600 mt-1">Codename: <strong><?= htmlspecialchars($product['codename']) ?></strong></p>
              <!-- Display Product Quantity -->
          <p class="text-sm text-gray-600">Bundle(packaged-1) & Pieces: 
            <strong><?= intval($product['quantity']) ?></strong>
          </p>


        <!-- Type Selection -->
        <div class="mt-4">
          <h4 class="font-semibold text-gray-700 mb-2">Select Type:</h4>
          <div class="flex flex-wrap gap-2">
            <?php
            $types->data_seek(0);
            foreach ($types as $index => $type): ?>
              <button
                type="button"
                onclick="showVariants(<?= $index ?>, '<?= addslashes($type['type_name']) ?>')"
                class="border p-2 rounded-lg hover:bg-gray-100 type-btn"
              >
                <img src="data:image/jpeg;base64,<?= base64_encode($type['type_image']) ?>" class="w-16 h-16 object-contain rounded mb-1" />
                <span class="block text-xs text-gray-700"><?= htmlspecialchars($type['type_name']) ?></span>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Variant Selection -->
        <div class="mt-6">
          <h4 class="font-semibold text-gray-700 mb-2">Available Colors & Sizes:</h4>
          <?php foreach ($type_names as $index => $type_name): ?>
            <div id="variants-<?= $index ?>" class="variant-group hidden flex gap-3 flex-wrap">
              <?php foreach ($type_variant_map[$type_name] as $variant): ?>
           <button 
  type="button" 
  onclick="selectVariant(this, '<?= addslashes($variant['color']) ?>')" 
  class="variant-btn text-center flex flex-col items-center justify-center p-2 border rounded hover:shadow transition"
  data-price="<?= $variant['price'] ?>" 
  data-percent="<?= $variant['percent'] ?>"
>
  <img src="data:image/jpeg;base64,<?= base64_encode($variant['image']) ?>" class="w-16 h-16 object-contain rounded mb-1" />
  <span class="text-sm"><?= htmlspecialchars($variant['color']) ?></span>
  <span class="text-xs text-gray-500"><?= htmlspecialchars($variant['size']) ?></span>
</button>

              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Hidden Form for Add to Cart -->
        <form action="cart/add_to_cart.php" method="POST" class="mt-6 space-y-2">
          <input type="hidden" name="product_id" value="<?= $product_id ?>">
          <input type="hidden" name="selected_type" id="selected_type">
          <input type="hidden" name="selected_variant" id="selected_variant">
          
    
          <!-- Total Price -->
          <div class="mt-2">
            <p class="text-gray-700">Total Price:</p>
            <p id="totalPrice" class="text-xl font-bold text-green-600">₱0.00</p>
          </div>

          <!-- Submit -->
          <button type="submit" class="bg-orange-600 text-white px-5 py-2 rounded hover:bg-orange-700">
            Add to Cart
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const groups = document.querySelectorAll('.variant-group');
    const productQuantity = <?= intval($product['quantity']) ?>;
    const basePrice = <?= $product['price'] !== null ? floatval($product['price']) : 0 ?>;
    let selectedVariantData = { price: 0, percent: 0 };

    function showVariants(index, typeName) {
      groups.forEach((group, i) => {
        group.classList.toggle('hidden', i !== index);
      });

      document.getElementById('selected_type').value = typeName;
      document.getElementById('selected_variant').value = '';
      document.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('selected-ring'));
      selectedVariantData = { price: 0, percent: 0 };
      updateTotalPrice();
    }

    function selectVariant(button, variantColor) {
      document.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('selected-ring'));
      button.classList.add('selected-ring');
      document.getElementById('selected_variant').value = variantColor;

      selectedVariantData.price = parseFloat(button.dataset.price);
      selectedVariantData.percent = parseFloat(button.dataset.percent);
      updateTotalPrice();
    }

function updateTotalPrice() {
  const variantPrice = selectedVariantData.price || 0;
  const percentMarkup = selectedVariantData.percent || 0;

  // Apply percent on variant price
  const priceWithPercent = variantPrice + (variantPrice * percentMarkup / 100);

  // Multiply by quantity from products table
  const total = priceWithPercent * productQuantity;

  document.getElementById('totalPrice').textContent = '₱' + total.toFixed(2);
}

  </script>
</body>
</html>
