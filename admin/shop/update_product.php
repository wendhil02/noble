<?php
include '../../connection/connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$product_id = $_GET['id'] ?? null;

if (!$product_id) {
  echo "Missing product ID.";
  exit;
}

// Fetch product
$product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();
$types = $conn->query("SELECT * FROM product_types WHERE product_id = $product_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<?php include '../navbar/top.php'; ?>

  <div class=" bg-white p-6 rounded-lg shadow mt-5">
    <h2 class="text-2xl font-bold mb-4 text-orange-600">Update Product</h2>

    <form action="update_process.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />

      <!-- Product Info -->
      <div class="mb-4">
        <label class="block font-semibold mb-1">Product Name</label>
        <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required class="w-full border p-2 rounded" />
      </div>
      <div class="mb-4">
        <label class="block font-semibold mb-1">Main Image (Leave blank if no change)</label>

        <?php if (!empty($product['main_image'])): ?>
          <div class="mb-2">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['main_image']); ?>"
              alt="Main Product Image"
              class="w-32 h-32 object-contain rounded shadow border" />
          </div>
        <?php endif; ?>

        <input type="file" name="main_image" accept="image/*" class="w-full" />
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-1">Codename</label>
        <input type="text" name="codename" value="<?php echo htmlspecialchars($product['codename']); ?>" required class="w-full border p-2 rounded" />
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-1">Quantity</label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required class="w-full border p-2 rounded" />
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border p-2 rounded" required><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
      </div>

      <!-- Product Types -->
      <div id="types-section">
        <?php
        $typeIndex = 0;
        while ($type = $types->fetch_assoc()) {
          $type_id = $type['id'];
          $variants = $conn->query("SELECT * FROM product_variants WHERE type_id = $type_id");
        ?>
          <div class="mb-6 border p-4 rounded bg-gray-50 relative" data-type-index="<?php echo $typeIndex; ?>">
            <div class="flex justify-between items-center mb-2">
              <h3 class="font-semibold text-lg text-gray-700">Product Type <?php echo $typeIndex + 1; ?></h3>
              <button type="button" onclick="removeType(this)" class="text-red-600 text-sm hover:underline">Remove Type</button>
            </div>

            <input type="hidden" name="type_id[]" value="<?php echo $type_id; ?>" />

            <div class="flex items-center gap-2 mt-1">
              <input type="checkbox" name="delete_type[]" value="<?php echo $type_id; ?>" />
              <label class="text-sm text-gray-600">Delete Type</label>
            </div>

            <div class="mb-3 flex gap-2 items-center">
              <input type="text" name="type_name[]" value="<?php echo htmlspecialchars($type['type_name']); ?>" placeholder="Type Name" class="border p-2 w-1/2 rounded" required />

              <div class="w-1/2">
                <?php if (!empty($type['type_image'])): ?>
                  <img src="data:image/jpeg;base64,<?php echo base64_encode($type['type_image']); ?>"
                    alt="Type Image"
                    class="w-20 h-20 object-contain rounded mb-1 border" />
                <?php endif; ?>
                <input type="file" name="type_image[]" accept="image/*" class="w-full" />
              </div>
            </div>


            <label class="block font-medium mb-1">Variants:</label>
            <div id="variant-section-<?php echo $typeIndex; ?>">
              <?php while ($variant = $variants->fetch_assoc()) { ?>
                <div class="flex gap-2 mb-2 items-center">
                  <input type="hidden" name="variant_id[<?php echo $typeIndex; ?>][]" value="<?php echo $variant['id']; ?>" />

                  <!-- Delete -->
                  <input type="checkbox" name="delete_variant[<?php echo $typeIndex; ?>][]" value="<?php echo $variant['id']; ?>" />
                  <label class="text-sm text-gray-600">Delete</label>

                  <!-- Color -->
                  <input type="text" name="variant_color[<?php echo $typeIndex; ?>][]" value="<?php echo htmlspecialchars($variant['color']); ?>" placeholder="Color" class="border p-2 w-1/5 rounded" />

                  <!-- Image -->
                  <div class="w-1/5">
                    <?php if (!empty($variant['image'])): ?>
                      <img src="data:image/jpeg;base64,<?php echo base64_encode($variant['image']); ?>" alt="Variant Image" class="w-12 h-12 object-contain rounded mb-1" />
                    <?php endif; ?>
                    <input type="file" name="variant_image[<?php echo $typeIndex; ?>][]" accept="image/*" />
                  </div>

                  <!-- Size -->
                  <input type="text" name="variant_size[<?php echo $typeIndex; ?>][]" value="<?php echo htmlspecialchars($variant['size']); ?>" placeholder="Size" class="border p-2 w-1/5 rounded" />

                  <!-- Base Price -->
                  <input
                    type="number"
                    step="0.01"
                    name="variant_price[<?php echo $typeIndex; ?>][]"
                    value="<?php echo htmlspecialchars($variant['price']); ?>"
                    placeholder="Base Price"
                    class="border p-2 w-1/5 rounded computed-price" />

                  <!-- Markup % -->
                  <input
                    type="number"
                    step="0.01"
                    name="variant_percent[<?php echo $typeIndex; ?>][]"
                    value="<?php echo htmlspecialchars($variant['percent'] ?? ''); ?>"
                    placeholder="Markup %"
                    class="border p-2 w-1/5 rounded percent-input" />

                  <!-- Markup Display -->
                  <div class="markup-preview text-sm text-green-600 w-1/5 font-semibold">₱0.00</div>

                  <!-- Discount -->
                  <input
                    type="number"
                    step="0.01"
                    name="variant_discount[<?php echo $typeIndex; ?>][]"
                    value="<?php echo htmlspecialchars($variant['discount'] ?? ''); ?>"
                    placeholder="Discount %"
                    class="border p-2 w-1/5 rounded discount-input" />

                  <!-- Final Price Display -->
                  <div class="final-preview text-sm text-red-600 w-1/5 font-semibold">₱0.00</div>

                  <!-- Name Variant -->
                  <input type="text" name="variant_namevariant[<?php echo $typeIndex; ?>][]" value="<?php echo htmlspecialchars($variant['namevariant'] ?? ''); ?>" placeholder="Name Variant" class="border p-2 w-1/5 rounded" />

                  <!-- Remove Button -->
                  <button type="button" onclick="removeVariant(this)" class="text-red-500 text-sm">X</button>
                </div>


              <?php } ?>
            </div>
            <button type="button" onclick="addVariant(<?php echo $typeIndex; ?>)" class="text-sm text-blue-600 mt-1">+ Add another variant</button>
          </div>
        <?php $typeIndex++;
        } ?>
      </div>

      <div class="mt-4">
        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Update Product</button>
      </div>
      <div class="mt-4">
        <button type="button" onclick="addType()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Type</button>
      </div>

    </form>
  </div>

  <script>
    let typeIndex = <?php echo $typeIndex; ?>;

    function removeType(button) {
      button.closest('[data-type-index]').remove();
    }

    function removeVariant(button) {
      button.parentElement.remove();
    }

    function createInput(type, name, placeholder, className = '') {
      const input = document.createElement('input');
      input.type = type;
      input.name = name;
      input.placeholder = placeholder;
      input.className = `border p-2 rounded ${className}`;
      return input;
    }

    function addVariant(index) {
      const variantSection = document.getElementById('variant-section-' + index);
      const div = document.createElement('div');
      div.classList.add('flex', 'gap-2', 'mb-2', 'items-center');

      // Hidden ID
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = `variant_id[${index}][]`;
      hiddenInput.value = 'new';
      div.appendChild(hiddenInput);

      // Delete placeholder
      const deleteLabel = document.createElement('div');
      deleteLabel.innerHTML = `<span class="text-sm text-gray-400 w-[40px] inline-block"></span>`;
      div.appendChild(deleteLabel);

      // Color
      const colorInput = createInput('text', `variant_color[${index}][]`, 'Color', 'w-1/5');
      div.appendChild(colorInput);

      // Image
      const imageInput = createInput('file', `variant_image[${index}][]`, '', 'w-1/5');
      imageInput.accept = 'image/*';
      div.appendChild(imageInput);

      // Size
      const sizeInput = createInput('text', `variant_size[${index}][]`, 'Size', 'w-1/5');
      div.appendChild(sizeInput);

      // Base Price
      const priceInput = createInput('number', `variant_price[${index}][]`, 'Base Price', 'w-1/5 computed-price');
      priceInput.step = '0.01';
      div.appendChild(priceInput);

      // Markup %
      const percentInput = createInput('number', `variant_percent[${index}][]`, 'Markup %', 'w-1/5 percent-input');
      percentInput.step = '0.01';
      div.appendChild(percentInput);

      // Markup Preview
      const markupDisplay = document.createElement('div');
      markupDisplay.className = 'markup-preview text-sm text-green-600 w-1/5 font-semibold';
      markupDisplay.textContent = '₱0.00';
      div.appendChild(markupDisplay);

      // Discount %
      const discountInput = createInput('number', `variant_discount[${index}][]`, 'Discount %', 'w-1/5 discount-input');
      discountInput.step = '0.01';
      div.appendChild(discountInput);

      // Final Price Preview
      const finalDisplay = document.createElement('div');
      finalDisplay.className = 'final-preview text-sm text-red-600 w-1/5 font-semibold';
      finalDisplay.textContent = '₱0.00';
      div.appendChild(finalDisplay);

      // Name Variant
      const nameVariantInput = createInput('text', `variant_namevariant[${index}][]`, 'Name Variant', 'w-1/5');
      div.appendChild(nameVariantInput);

      // Remove Button
      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'text-red-500 text-sm';
      removeButton.innerHTML = 'X';
      removeButton.onclick = () => removeVariant(removeButton);
      div.appendChild(removeButton);

      variantSection.appendChild(div);

      // Hook live update
      priceInput.addEventListener('input', () => applyMarkup(priceInput, percentInput, discountInput, markupDisplay, finalDisplay));
      percentInput.addEventListener('input', () => applyMarkup(priceInput, percentInput, discountInput, markupDisplay, finalDisplay));
      discountInput.addEventListener('input', () => applyMarkup(priceInput, percentInput, discountInput, markupDisplay, finalDisplay));
    }

    function addType() {
      const typesSection = document.getElementById('types-section');
      const div = document.createElement('div');
      div.className = 'mb-6 border p-4 rounded bg-gray-50 relative';
      div.setAttribute('data-type-index', typeIndex);

      div.innerHTML = `
      <div class="flex justify-between items-center mb-2">
        <h3 class="font-semibold text-lg text-gray-700">Product Type ${typeIndex + 1}</h3>
        <button type="button" onclick="removeType(this)" class="text-red-600 text-sm hover:underline">Remove Type</button>
      </div>
      <input type="hidden" name="type_id[]" value="new" />
      <div class="mb-3 flex gap-2 items-center">
        <input type="text" name="type_name[]" placeholder="Type Name" class="border p-2 w-1/2 rounded" required />
        <input type="file" name="type_image[]" accept="image/*" class="w-1/2" />
      </div>
      <label class="block font-medium mb-1">Variants:</label>
      <div id="variant-section-${typeIndex}"></div>
      <button type="button" onclick="addVariant(${typeIndex})" class="text-sm text-blue-600 mt-1">+ Add another variant</button>
    `;

      typesSection.appendChild(div);
      typeIndex++;
    }

    function applyMarkup(priceInput, percentInput, discountInput, markupDisplay, finalDisplay) {
      const base = parseFloat(priceInput.value);
      const percent = parseFloat(percentInput.value);
      const discount = parseFloat(discountInput?.value || 0);

      if (!isNaN(base) && !isNaN(percent)) {
        const computed = base + (base * percent / 100);
        markupDisplay.textContent = '₱' + computed.toFixed(2);

        if (!isNaN(discount)) {
          const final = computed - (computed * discount / 100);
          finalDisplay.textContent = '₱' + final.toFixed(2);
        } else {
          finalDisplay.textContent = '₱' + computed.toFixed(2);
        }
      } else {
        markupDisplay.textContent = '₱0.00';
        finalDisplay.textContent = '₱0.00';
      }
    }

    // Hook inputs on page load (existing data)
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.percent-input').forEach((percentInput) => {
        const parent = percentInput.closest('.flex');
        const priceInput = parent.querySelector('.computed-price');
        const discountInput = parent.querySelector('.discount-input');
        const markupDisplay = parent.querySelector('.markup-preview');
        const finalDisplay = parent.querySelector('.final-preview');

        if (priceInput && markupDisplay && finalDisplay) {
          const hook = () => applyMarkup(priceInput, percentInput, discountInput, markupDisplay, finalDisplay);

          priceInput.addEventListener('input', hook);
          percentInput.addEventListener('input', hook);
          if (discountInput) discountInput.addEventListener('input', hook);

          // Trigger initial display
          hook();
        }
      });
    });
  </script>


</body>

</html>