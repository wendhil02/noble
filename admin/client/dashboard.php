<?php
session_start();
include '../../connection/connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Client Info</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        setTimeout(() => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) alertBox.style.display = 'none';
        }, 5000);
    </script>
</head>

<body class="bg-gray-100 min-h-screen font-sans">

    <?php include '../navbar/top.php'; ?>

    <!-- Access Denied Alert -->
    <?php if (isset($_SESSION['access_denied'])): ?>
        <div id="alertBox" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md max-w-md mx-auto mt-6 text-sm">
            <strong class="font-bold">Access Denied:</strong>
            <span class="block"><?php echo htmlspecialchars($_SESSION['access_denied']); ?></span>
        </div>
        <?php unset($_SESSION['access_denied']); ?>
    <?php endif; ?>

    <!-- Page Header -->
    <div class=" px-4 sm:px-6 lg:px-8 mt-10">
        <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Client Info Table</h2>

        <!-- Table Container -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-200 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                      
                        <th class="px-4 py-3">Reference No</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Address</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Contact</th>
                        <th class="px-4 py-3">Country</th>
                        <th class="px-4 py-3">Client Type</th>
                        <th class="px-4 py-3">Sex</th>
                        <th class="px-4 py-3">Status</th>
                      
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $query = "SELECT * FROM client_info ORDER BY created_at DESC";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                            <tr class="hover:bg-gray-50">
                              
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['reference_no']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['address']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['contact']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['country']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['client_type']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($row['sex']); ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-1 text-xs rounded 
                                        <?= $row['status'] === 'Confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                              
                            </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="11" class="text-center px-6 py-4 text-gray-500">No client records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
