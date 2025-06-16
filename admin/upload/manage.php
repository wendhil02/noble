<?php
include '../../connection/connect.php';

// Validate variant_id
if (!isset($_GET['variant_id']) || !is_numeric($_GET['variant_id'])) {
    echo "<p style='color:red;'>Error: Variant ID is missing or invalid.</p>";
    exit;
}

$variant_id = (int) $_GET['variant_id'];

// Fetch variant name (optional for display)
$variant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM variants WHERE id = $variant_id"));
$variant_name = $variant ? $variant['name'] : "Unknown";

// Fetch images for that variant
$images = mysqli_query($conn, "SELECT * FROM images WHERE variant_id = $variant_id");
if (!$images) {
    echo "<p style='color:red;'>Failed to fetch images.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Images for <?= htmlspecialchars($variant_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6 font-sans">

    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-orange-600 mb-6">Manage Images for Variant: <?= htmlspecialchars($variant_name) ?></h2>

        <?php while ($row = mysqli_fetch_assoc($images)) : ?>
            <div class="bg-white rounded shadow p-4 mb-6 flex items-start space-x-4">
                <img src="data:image/jpeg;base64,<?= base64_encode($row['image_data']) ?>" class="w-32 h-32 object-contain rounded border">
                
                <div class="flex-1">
                    <!-- Update Form -->
                    <form action="managecrud/update.php" method="POST" class="mb-2">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <label class="block text-sm text-gray-600 mb-1">Description:</label>
                        <textarea name="description" class="w-full border p-2 rounded" required><?= htmlspecialchars($row['description']) ?></textarea>
                        <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    </form>

                    <!-- Delete Form -->
                    <form action="managecrud/delete.php" method="POST" onsubmit="return confirm('Delete this image?')">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>

        <a href="variant_selected.php" class="text-blue-600 hover:underline">&larr; Back to Variant List</a>
    </div>

</body>
</html>
