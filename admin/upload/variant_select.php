<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select Variant</title>
</head>
<body>

<h2>Step 1: Enter Variant Name</h2>

<form action="start_upload.php" method="POST">
  <input type="text" name="variant_name" placeholder="e.g., Marine" required>
  <br><br>
  <button type="submit">Next</button>
</form>

</body>
</html>
