<?php
include '../../connection/connect.php';

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 1;

if ($user_id && $admin_id) {
    // Mark all unread messages from this user to admin as read
    $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0");
    $stmt->bind_param("ii", $user_id, $admin_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Messages marked as read']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error marking messages as read']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
}
?>