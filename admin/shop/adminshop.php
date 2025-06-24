<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Product Upload</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
  <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-orange-600">Add Product</h2>

    <form action="upload_process.php" method="POST" enctype="multipart/form-data">
      <!-- Product Info -->
      <div class="mb-4">
        <label class="block font-semibold mb-1">Product Name</label>
        <input type="text" name="product_name" required class="w-full border p-2 rounded" />
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-1">Main Image</label>
        <input type="file" name="main_image" accept="image/*" required class="w-full" />
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-1">Codename</label>
        <input type="text" name="codename" required class="w-full border p-2 rounded" />
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-1">Quantity</label>
        <input type="number" name="quantity" required class="w-full border p-2 rounded" />
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border p-2 rounded resize-none" placeholder="Write product description here..." required></textarea>
      </div>

      <!-- Dynamic Types + Variants -->
      <div id="types-section">
        <!-- Dynamic product types will appear here -->
      </div>

      <button type="button" onclick="addType()" class="bg-blue-600 text-white px-3 py-1 rounded text-sm mt-2">+ Add Product Type</button>

      <!-- Submit -->
      <div class="mt-6">
        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Upload Product</button>
      </div>

      <a href="adminupdateshop" class="text-blue-600 hover:underline">
        <div class="mt-6">
          <button type="button" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Update Product</button>
        </div>
      </a>
    </form>
  </div>

  <script>
    let typeIndex = 0;

    function addType() {
      const section = document.getElementById('types-section');
      const wrapper = document.createElement('div');
      wrapper.classList.add('mb-6', 'border', 'p-4', 'rounded', 'bg-gray-50', 'relative');
      wrapper.setAttribute('data-type-index', typeIndex);

      wrapper.innerHTML = `
        <div class="flex justify-between items-center mb-2">
          <h3 class="font-semibold text-lg text-gray-700">Product Type ${typeIndex + 1}</h3>
          <button type="button" onclick="removeType(this)" class="text-red-600 text-sm hover:underline">Remove Type</button>
        </div>
        <div class="mb-3 flex gap-2">
          <input type="text" name="type_name[]" placeholder="Type Name" class="border p-2 w-1/2 rounded" required />
          <input type="file" name="type_image[]" accept="image/*" class="w-1/2 mt-1" required />
        </div>
        <div>
          <label class="block font-medium mb-1">Variant Options:</label>
          <div id="variant-section-${typeIndex}">
            ${variantRowHTML(typeIndex)}
          </div>
          <button type="button" onclick="addVariant(${typeIndex})" class="text-sm text-blue-600 mt-1">+ Add another variant</button>
        </div>
      `;

      section.appendChild(wrapper);
      typeIndex++;
    }

    function variantRowHTML(index) {
      return `
        <div class="flex gap-2 mb-2 items-center">
          <input type="text" name="variant_color[${index}][]" placeholder="Color Name" class="border p-2 w-1/6 rounded" />
          <input type="file" name="variant_image[${index}][]" accept="image/*" class="w-1/6" />
          <input type="text" name="variant_size[${index}][]" placeholder="Size" class="border p-2 w-1/6 rounded" />
          <input type="number" name="variant_price[${index}][]" placeholder="Price" class="border p-2 w-1/6 rounded" />
         <input type="number" name="variant_percent[${index}][]" placeholder="Percent" class="border p-2 w-1/6 rounded" oninput="updatePriceFromPercent(this)" />
          <input type="text" name="variant_namevariant[${index}][]" placeholder="Name Variant" class="border p-2 w-1/6 rounded" />
          <input type="number" step="0.01" name="variant_discount[${index}][]" placeholder="Discount" class="border p-2 w-1/6 rounded" />
          <button type="button" onclick="removeVariant(this)" class="text-red-500 text-sm">✕</button>
        </div>
      `;
    }

    function addVariant(index) {
      const variantSection = document.getElementById(`variant-section-${index}`);
      const div = document.createElement('div');
      div.classList.add('flex', 'gap-2', 'mb-2', 'items-center');
      div.innerHTML = variantRowHTML(index);
      variantSection.appendChild(div);
    }

    function removeType(button) {
      button.closest('[data-type-index]').remove();
    }

    function removeVariant(button) {
      button.parentElement.remove();
    }


    function updatePriceFromPercent(percentInput) {
  const parent = percentInput.closest('.flex');
  const priceInput = parent.querySelector('input[name^="variant_price"]');
  
  const basePrice = parseFloat(priceInput.value) || 0;
  const percent = parseFloat(percentInput.value) || 0;

  const finalPrice = basePrice + (basePrice * percent / 100);
  priceInput.value = finalPrice.toFixed(2);
}

  </script>
</body>

</html>
