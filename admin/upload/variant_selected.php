<?php
include '../../connection/connect.php';

$variants = mysqli_query($conn, "SELECT * FROM variants");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Variant</title>
</head>
<body>

<h2>Select a Variant to Manage Images</h2>

<?php while ($variant = mysqli_fetch_assoc($variants)) : ?>
    <div style="margin-bottom: 10px;">
        <a href="manage.php?variant_id=<?= $variant['id'] ?>" style="font-size: 18px;">
            <?= htmlspecialchars($variant['name']) ?>
        </a>
    </div>
<?php endwhile; ?>

</body>
</html>
