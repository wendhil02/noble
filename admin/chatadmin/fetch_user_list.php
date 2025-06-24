<?php
include '../../connection/connect.php';

$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 1;

// Get users with message data
$stmt = $conn->prepare("
    SELECT 
        u.id, 
        u.name, 
        u.email,
        MAX(m.message) as last_message,
        MAX(m.created_at) as last_message_time,
        COUNT(m.id) as message_count,
        SUM(CASE WHEN m.receiver_id = ? THEN 1 ELSE 0 END) as messages_to_admin,
        SUM(CASE WHEN m.receiver_id = ? AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count
    FROM users u 
    INNER JOIN messages m ON u.id = m.sender_id OR u.id = m.receiver_id
    WHERE u.id != ? AND (m.sender_id = u.id OR m.receiver_id = u.id)
    GROUP BY u.id, u.name, u.email
    ORDER BY last_message_time DESC
");
$stmt->bind_param("iii", $admin_id, $admin_id, $admin_id);
$stmt->execute();
$users = $stmt->get_result();

if ($users->num_rows > 0):
    while ($user = $users->fetch_assoc()):
        $unread_badge = $user['unread_count'] > 0 ? 
            '<span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full ml-2">' . $user['unread_count'] . '</span>' : '';
        
        $last_message = $user['last_message'] ? 
            (strlen($user['last_message']) > 30 ? substr($user['last_message'], 0, 30) . '...' : $user['last_message']) : 
            'No messages yet';
        
        $time_display = $user['last_message_time'] ? 
            date('M j, g:i A', strtotime($user['last_message_time'])) : '';
?>
        <div class="user-item p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors" 
             data-user-id="<?= $user['id'] ?>">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800 truncate"><?= htmlspecialchars($user['name']) ?></h3>
                        <?= $unread_badge ?>
                    </div>
                    <p class="text-sm text-gray-600 truncate"><?= htmlspecialchars($user['email']) ?></p>
                    <p class="text-xs text-gray-500 truncate mt-1"><?= htmlspecialchars($last_message) ?></p>
                    <?php if ($time_display): ?>
                        <p class="text-xs text-gray-400 mt-1"><?= $time_display ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php 
    endwhile;
else: 
?>
    <div class="p-8 text-center text-gray-500">
        <div class="w-12 h-12 bg-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"></path>
            </svg>
        </div>
        <p class="font-medium">No conversations yet</p>
        <p class="text-sm mt-1">Users who send messages will appear here</p>
    </div>
<?php endif; ?>