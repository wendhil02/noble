<?php
include '../connection/connect.php';

$product_id = $_GET['id'] ?? 0;

if (!$product_id || !is_numeric($product_id)) {
  echo "Invalid product ID.";
  exit;
}

// ✅ Fetch product including description
$query = "SELECT id, product_name, codename, quantity, price, main_image, description FROM products WHERE id = $product_id LIMIT 1";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
  echo "Product not found.";
  exit;
}

// ✅ Fetch product types
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['product_name']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <style>
    .selected-ring {
      outline: 3px solid #f97316;
      outline-offset: 2px;
      box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.2);
    }

    .type-selected {
      background: linear-gradient(135deg, #fed7aa, #fdba74);
      border-color: #f97316;
      transform: scale(1.02);
    }

    .variant-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .price-gradient {
      background: linear-gradient(135deg, #059669, #10b981);
    }

    .fade-in {
      animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-orange-50 min-h-screen">

  <?php include 'navbar/top.php'; ?>

  <?php if (isset($_SESSION['toast'])): ?>
    <div class="fixed top-5 right-5 z-50 bg-green-500 border border-green-400 text-white px-4 py-3 rounded shadow-lg animate-fade-in">
        <?= htmlspecialchars($_SESSION['toast']['message']) ?>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.querySelector('.fixed.z-50');
            if (toast) toast.remove();
        }, 3000);
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>


  <!-- Navigation breadcrumb -->
  <div class="max-w-8xl mx-auto px-4 py-4">
    <nav class="text-sm text-gray-600">
      <a href="index" class="hover:text-orange-600 cursor-pointer">Home</a>
      <span class="mx-2">›</span>
      <a href="shop" class="hover:text-orange-600 cursor-pointer">Products</a>
      <span class="mx-2">›</span>
      <span class="text-orange-600 font-medium"><?= htmlspecialchars($product['product_name']) ?></span>
    </nav>
  </div>


  <div class="max-w-9xl mx-auto px-4 pb-9">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
      <div class="grid lg:grid-cols-2 gap-0">
        <!-- Product Image Section -->
        <div class="bg-white px-4 py-10 lg:px-12 max-w-4xl mx-auto">
          <!-- Image Container -->
          <div class="aspect-square w-[300px] sm:w-[400px] md:w-[500px] mx-auto relative overflow-hidden rounded-xl shadow-lg bg-white">
            <!-- Product Image -->
            <img
              src="data:image/jpeg;base64,<?= base64_encode($product['main_image']) ?>"
              class="w-full h-full object-contain transition-transform duration-500 hover:scale-105"
              alt="<?= htmlspecialchars($product['product_name']) ?>" />

            <!-- Triangle Icon in Top-Left -->
            <div class="absolute top-0 left-0 w-12 h-12 z-10">
              <div class="w-12 h-12 bg-blue-400 clip-triangle relative">
                <img src="img/icon/b.png" alt="Check Icon" class="absolute top-1.5 left-1.5 w-5 h-5 object-contain" />
              </div>
            </div>

            <!-- Featured Badge -->
            <div class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-medium">
              Featured
            </div>
          </div>

          <!-- Description Section -->
          <div class="mt-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
              <?= htmlspecialchars($product['product_name']) ?>
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
              <?= !empty($product['description']) ? htmlspecialchars($product['description']) : 'No description available.' ?>
            </p>
          </div>
        </div>

        <!-- Clip path style for triangle -->
        <style>
          .clip-triangle {
            clip-path: polygon(0 0, 100% 0, 0 100%);
          }
        </style>

        <!-- Product Info Section -->
        <div class="p-8 lg:p-12 space-y-8">
          <!-- Product Header -->
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                <?= htmlspecialchars($product['product_name']) ?>
              </h1>
              <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
            </div>

            <div class="flex flex-wrap gap-4 text-sm">
              <div class="flex items-center gap-2 bg-orange-100 text-orange-800 px-3 py-1 rounded-full">
                <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                <span class="font-medium">Code: <?= htmlspecialchars($product['codename']) ?></span>
              </div>

            </div>
          </div>

          <!-- Type Selection -->
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <h3 class="text-xl font-bold text-gray-900">Select Type</h3>
              <div class="h-px bg-gradient-to-r from-orange-300 to-transparent flex-1"></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
              <?php
              $types->data_seek(0);
              foreach ($types as $index => $type): ?>
                <button
                  type="button"
                  onclick="showVariants(<?= $index ?>, '<?= addslashes($type['type_name']) ?>')"
                  class="type-btn group relative bg-white border-2 border-gray-200 rounded-xl p-4 hover:border-orange-300 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">

                  <div class="aspect-square bg-gray-50 rounded-lg mb-3 overflow-hidden">
                    <img
                      src="data:image/jpeg;base64,<?= base64_encode($type['type_image']) ?>"
                      class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                      alt="<?= htmlspecialchars($type['type_name']) ?>" />
                  </div>

                  <span class="block text-sm font-semibold text-gray-800 text-center">
                    <?= htmlspecialchars($type['type_name']) ?>
                  </span>

                  <!-- Selection indicator -->
                  <div class="absolute top-2 right-2 w-4 h-4 bg-orange-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                  </div>
                </button>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Variant Selection -->
          <!-- Variant Selection -->
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <h3 class="text-xl font-bold text-gray-900">Colors & Sizes</h3>
              <div class="h-px bg-gradient-to-r from-orange-300 to-transparent flex-1"></div>
            </div>

            <?php foreach ($type_names as $index => $type_name): ?>
              <div id="variants-<?= $index ?>" class="variant-group hidden fade-in">
                <!-- Swiper Container -->
                <div class="swiper swiper-variant-<?= $index ?>">
                  <div class="swiper-wrapper p-4">
                    <?php foreach ($type_variant_map[$type_name] as $variant):
                      $price = floatval($variant['price']);
                      $percent = floatval($variant['percent']);
                      $discount = floatval($variant['discount'] ?? 0);

                      $priceWithMarkup = $price + ($price * $percent / 100);
                      $finalPrice = $priceWithMarkup - ($priceWithMarkup * $discount / 100);
                    ?>
                      <div class="swiper-slide">
                        <button
                          type="button"
                          onclick="selectVariant(this, '<?= addslashes($variant['color']) ?>')"
                          class="variant-btn variant-hover group relative bg-white border-2 border-gray-200 rounded-xl p-4 hover:border-orange-300 hover:shadow-lg transition-all duration-300 w-full"
                          data-price="<?= $price ?>"
                          data-percent="<?= $percent ?>"
                          data-discount="<?= $discount ?>">
                          <div class="aspect-square bg-gray-50 rounded-lg mb-3 overflow-hidden">
                            <img
                              src="data:image/jpeg;base64,<?= base64_encode($variant['image']) ?>"
                              class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                              alt="<?= htmlspecialchars($variant['color']) ?>" />
                          </div>

                          <div class="text-center space-y-1 break-words">
                            <h4 class="text-sm font-semibold text-gray-800">
                              <?= htmlspecialchars($variant['namevariant']) ?>
                            </h4>

                            <ul class="text-center text-sm text-gray-800 space-y-1">
                              <li><span class="font-semibold">Color:</span> <?= htmlspecialchars($variant['color']) ?></li>
                              <li><span class="font-semibold">Size:</span> <?= htmlspecialchars($variant['size']) ?></li>
                            </ul>

                            <?php if ($discount > 0): ?>
                              <span class="block text-xs text-gray-400 line-through">₱<?= number_format($priceWithMarkup, 2) ?></span>
                              <span class="block text-md text-red-500 font-semibold">₱<?= number_format($finalPrice, 2) ?></span>
                              <div class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full">
                                <?= number_format($discount, 0) ?>% OFF
                              </div>
                            <?php else: ?>
                              <span class="block text-md text-gray-800 font-medium">₱<?= number_format($finalPrice, 2) ?></span>
                            <?php endif; ?>
                          </div>

                          <!-- Selection check -->
                          <div class="absolute top-2 right-2 w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center opacity-0 transition-opacity">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                          </div>
                        </button>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <div class="swiper-button-prev swiper-button-prev-<?= $index ?>"></div>
                  <div class="swiper-button-next swiper-button-next-<?= $index ?>"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Purchase Section -->
          <div class="border-t pt-8">
            <form action="cart/add_to_cart.php" method="POST" class="space-y-6">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <input type="hidden" name="selected_type" id="selected_type">
              <input type="hidden" name="selected_variant" id="selected_variant">
              <input type="hidden" name="return_url" value="product_view.php?id=<?= $product['id'] ?>">

              <!-- Price Display -->
              <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-gray-600 mb-1">Total Price</p>
                    <p id="totalPrice" class="text-3xl font-bold text-green-600">₱0.00</p>
                  </div>
                </div>
              </div>

              <!-- Add to Cart Button -->
              <button
                type="submit"
                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-3 group">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                </svg>
                Proceed
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const variantGroups = document.querySelectorAll(".variant-group");

        variantGroups.forEach((group, index) => {
          new Swiper(`.swiper-variant-${index}`, {
            slidesPerView: 1.2,
            spaceBetween: 10,
            navigation: {
              nextEl: `.swiper-button-next-${index}`,
              prevEl: `.swiper-button-prev-${index}`
            },
            breakpoints: {
              640: {
                slidesPerView: 2.2
              },
              768: {
                slidesPerView: 2
              },
              1024: {
                slidesPerView: 3
              }
            }
          });
        });
      });

      const groups = document.querySelectorAll('.variant-group');
      const productQuantity = <?= intval($product['quantity']) ?>;
      const basePrice = <?= $product['price'] !== null ? floatval($product['price']) : 0 ?>;
      let selectedVariantData = {
        price: 0,
        percent: 0,
        discount: 0
      };

      function showVariants(index, typeName) {
        // Hide all variant groups
        groups.forEach((group, i) => {
          group.classList.toggle('hidden', i !== index);
        });

        // Update type selection styling
        document.querySelectorAll('.type-btn').forEach((btn, i) => {
          btn.classList.toggle('type-selected', i === index);
        });

        // Update form data
        document.getElementById('selected_type').value = typeName;
        document.getElementById('selected_variant').value = '';

        // Reset variant selection
        document.querySelectorAll('.variant-btn').forEach(btn => {
          btn.classList.remove('selected-ring');
          btn.querySelector('.absolute').classList.add('opacity-0');
        });

        selectedVariantData = {
          price: 0,
          percent: 0
        };
        updateTotalPrice();
      }

      function selectVariant(button, variantColor) {
        // Remove previous selection
        document.querySelectorAll('.variant-btn').forEach(btn => {
          btn.classList.remove('selected-ring');
          btn.querySelector('.absolute').classList.add('opacity-0');
        });

        // Add selection to current button
        button.classList.add('selected-ring');
        button.querySelector('.absolute').classList.remove('opacity-0');

        // Update form data
        document.getElementById('selected_variant').value = variantColor;

        // Update price data
        selectedVariantData.price = parseFloat(button.dataset.price);
        selectedVariantData.percent = parseFloat(button.dataset.percent);
        selectedVariantData.discount = parseFloat(button.dataset.discount);

        updateTotalPrice();
      }

      function updateTotalPrice() {
        const variantPrice = selectedVariantData.price || 0;
        const percentMarkup = selectedVariantData.percent || 0;
        const discount = selectedVariantData.discount || 0;

        const priceWithPercent = variantPrice + (variantPrice * percentMarkup / 100);
        const priceWithDiscount = priceWithPercent - (priceWithPercent * discount / 100);
        const total = priceWithDiscount * productQuantity;

        document.getElementById('totalPrice').textContent = '₱' + total.toLocaleString('en-US', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      }
    </script>
</body>

</html>