<?php
include '../../connection/connect.php';

if (!isset($_GET['variant_id']) || !is_numeric($_GET['variant_id'])) {
    echo "<p style='color:red;'>Error: Variant ID is missing or invalid.</p>";
    exit;
}

$variant_id = (int) $_GET['variant_id']; // safely cast to integer
$images = mysqli_query($conn, "SELECT * FROM images WHERE variant_id = $variant_id");

if (!$images) {
    echo "<p style='color:red;'>Failed to fetch images.</p>";
    exit;
}
?>

<h2>Manage Images</h2>

<?php while ($row = mysqli_fetch_assoc($images)) : ?>
    <div style="margin-bottom:20px;">
        <img src="data:image/jpeg;base64,<?= base64_encode($row['image_data']) ?>" width="100"><br>
        <form action="update.php" method="POST" style="display:inline-block;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <textarea name="description" required><?= htmlspecialchars($row['description']) ?></textarea>
            <button type="submit">Update</button>
        </form>

        <form action="delete.php" method="POST" onsubmit="return confirm('Delete this image?')" style="display:inline-block;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit">Delete</button>
        </form>
    </div>
<?php endwhile; ?>
