<?php
include '../../connection/connect.php';

header('Content-Type: application/json');

$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 1;

// Validate input
if (empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
    exit;
}

if (!$receiver_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid receiver']);
    exit;
}

// Check if receiver exists
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $receiver_id);
$stmt->execute();
$user_exists = $stmt->get_result()->num_rows > 0;

if (!$user_exists) {
    echo json_encode(['success' => false, 'message' => 'Receiver not found']);
    exit;
}

// Insert message
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at, is_read) VALUES (?, ?, ?, NOW(), 0)");
$stmt->bind_param("iis", $admin_id, $receiver_id, $message);

if ($stmt->execute()) {
    $message_id = $conn->insert_id;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Message sent successfully',
        'message_id' => $message_id,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Error sending message: ' . $conn->error
    ]);
}
?>