<?php
include '../../connection/connect.php';

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 1;

if (!$user_id) {
    echo '<div class="flex-1 flex items-center justify-center bg-gray-50">
            <div class="text-center text-gray-500">
                <p>Invalid user selection</p>
            </div>
          </div>';
    exit;
}

// Get selected user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$selected_user = $stmt->get_result()->fetch_assoc();

if (!$selected_user) {
    echo '<div class="flex-1 flex items-center justify-center bg-gray-50">
            <div class="text-center text-gray-500">
                <p>User not found</p>
            </div>
          </div>';
    exit;
}

// Get messages between selected user and admin
$stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
$stmt->bind_param("iiii", $user_id, $admin_id, $admin_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!-- Chat Header -->
<div class="p-4 bg-white border-b border-gray-200">
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">
        <?= strtoupper(substr($selected_user['name'], 0, 1)) ?>
      </div>
      <div>
        <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($selected_user['name']) ?></h3>
        <p class="text-sm text-gray-600"><?= htmlspecialchars($selected_user['email']) ?></p>
      </div>
    </div>
    <div class="text-sm text-gray-500">
      Acting as: <span class="font-medium text-orange-600">Admin (ID <?= $admin_id ?>)</span>
    </div>
  </div>
</div>

<!-- Messages -->
<div id="chat-box" class="flex-1 overflow-y-auto p-4 bg-gray-50 chat-scroll">
  <div id="messages-container">
    <?php if ($messages && $messages->num_rows > 0): ?>
      <?php while ($message = $messages->fetch_assoc()): ?>
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
      <?php endwhile; ?>
    <?php else: ?>
      <div class="text-center text-gray-500 mt-8">
        <p>No messages yet. Start the conversation!</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Scroll Button -->
<button id="scrollBtn"
  class="fixed bottom-24 right-8 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-full shadow-lg hidden z-50">
  ↓ Scroll to Bottom
</button>

<!-- Message Input -->
<div class="p-3 bg-white border-t border-gray-200">
  <form id="messageForm" class="space-y-3">
    <input type="hidden" id="receiver_id" value="<?= $user_id ?>">
    <div class="flex gap-2">
      <textarea id="messageInput" placeholder="Type your admin reply..." rows="2" required
        class="flex-1 p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"></textarea>
      <button type="submit"
        class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600 transition self-end">
        Send as Admin
      </button>
    </div>
  </form>
</div>