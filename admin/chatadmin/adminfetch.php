<?php
include '../../connection/connect.php';

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 1;

if (!$user_id) {
    echo '<div class="text-center text-gray-500 mt-8"><p>Invalid user ID</p></div>';
    exit;
}

// Get messages between selected user and admin
$stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
$stmt->bind_param("iiii", $user_id, $admin_id, $admin_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();

if ($messages && $messages->num_rows > 0):
    while ($message = $messages->fetch_assoc()):
?>
        <div class="flex <?= $message['sender_id'] == $admin_id ? 'justify-end' : 'justify-start' ?> mb-4">
            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg <?= $message['sender_id'] == $admin_id ? 'bg-orange-500 text-white' : 'bg-white text-gray-800 border border-gray-200' ?>">
                <p class="break-words"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                <div class="flex justify-between items-center mt-1">
                    <p class="text-xs <?= $message['sender_id'] == $admin_id ? 'text-orange-100' : 'text-gray-500' ?>">
                        <?= $message['sender_id'] == $admin_id ? 'Admin' : 'User' ?>
                    </p>
                    <p class="text-xs <?= $message['sender_id'] == $admin_id ? 'text-orange-100' : 'text-gray-500' ?>">
                        <?= date('M j, g:i A', strtotime($message['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>
<?php 
    endwhile;
else: 
?>
    <div class="text-center text-gray-500 mt-8">
        <div class="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
        </div>
        <p class="font-medium">No messages yet</p>
        <p class="text-sm mt-1">Start the conversation by sending a message!</p>
    </div>
<?php endif; ?>