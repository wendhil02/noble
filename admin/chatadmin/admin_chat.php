<?php
include '../../connection/connect.php';

// Get selected admin ID or default to 1
$admin_id = isset($_GET['admin_id']) ? intval($_GET['admin_id']) : 1;

// Get all users for admin selection
$stmt = $conn->prepare("SELECT id, name FROM users ORDER BY name ASC");
$stmt->execute();
$all_users = $stmt->get_result();

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
    INNER JOIN messages m ON u.id = m.sender_id
    WHERE u.id > 0
    GROUP BY u.id, u.name, u.email
    ORDER BY last_message_time DESC
");
$stmt->bind_param("ii", $admin_id, $admin_id);
$stmt->execute();
$users = $stmt->get_result();

// Get selected user messages
$selected_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$messages = null;
$selected_user = null;

if ($selected_user_id) {
  // ✅ Mark unread messages as read
  $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0");
  $stmt->bind_param("ii", $selected_user_id, $admin_id);
  $stmt->execute();

  // Get selected user info
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param("i", $selected_user_id);
  $stmt->execute();
  $selected_user = $stmt->get_result()->fetch_assoc();

  // Get messages between selected user and admin
  $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
  $stmt->bind_param("iiii", $selected_user_id, $admin_id, $admin_id, $selected_user_id);
  $stmt->execute();
  $messages = $stmt->get_result();
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Admin - Message Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .chat-scroll::-webkit-scrollbar {
      width: 6px;
    }

    .chat-scroll::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .chat-scroll::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }

    .user-list::-webkit-scrollbar {
      width: 6px;
    }

    .user-list::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .user-list::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen">
<?php include '../navbar/top.php'; ?>
  <div class="flex h-screen">
    <!-- Users Sidebar -->
    <div class="w-1/3 bg-white border-r border-gray-300 flex flex-col">
      <!-- Header -->
      <div class="p-4 border-b border-gray-200 bg-orange-600 text-white">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-bold">Admin Dashboard</h2>
        </div>
        <p class="text-orange-100 text-sm mt-1">All users who sent messages</p>
      </div>

      <!-- Admin Selector -->
      <div class="p-4 border-b border-gray-200">
        <select id="adminSelector" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
          <?php while ($user = $all_users->fetch_assoc()): ?>
            <option value="<?= $user['id'] ?>" <?= $user['id'] == $admin_id ? 'selected' : '' ?>>
              <?= htmlspecialchars($user['name']) ?> (ID: <?= $user['id'] ?>)
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Search -->
      <div class="p-4 border-b border-gray-200">
        <input type="text" id="userSearch" placeholder="Search users..."
          class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>

      <!-- Users List -->
      <div class="flex-1 overflow-y-auto user-list" id="usersList">
        <!-- This gets loaded by AJAX -->
      </div>

    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col" id="chat-panel">
      <!-- Default empty state -->
      <div class="flex-1 flex items-center justify-center bg-gray-50">
        <div class="text-center text-gray-500">
          <div class="w-16 h-16 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium mb-2">Select a conversation</h3>
          <p>Choose a user from the sidebar to start messaging</p>
          <p class="text-xs mt-2 text-gray-400">Showing all users who have sent messages</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentUserId = null;
    let currentAdminId = <?= $admin_id ?>;
    let messagesRefreshInterval = null;

    function fetchUserListWithNotifications() {
      const selectedAdminId = $("#adminSelector").val() || currentAdminId;

      $.ajax({
        url: "fetch_user_list.php",
        method: "POST",
        data: {
          admin_id: selectedAdminId
        },
        success: function(data) {
          $("#usersList").html(data);
          
          // Maintain selection highlight if user was previously selected
          if (currentUserId) {
            $(`.user-item[data-user-id="${currentUserId}"]`).addClass("bg-orange-50 border-l-4 border-l-orange-500");
          }
        },
        error: function() {
          console.log("Error updating user list with notifications.");
        }
      });
    }

    function loadUserChat(userId, adminId) {
      // Clear previous message refresh interval
      if (messagesRefreshInterval) {
        clearInterval(messagesRefreshInterval);
      }

      currentUserId = userId;
      currentAdminId = adminId;

      // Mark messages as read and load chat
      $.ajax({
        url: "mark_messages_read.php", // You'll need to create this file
        method: "POST",
        data: {
          user_id: userId,
          admin_id: adminId
        }
      });

      // Load the chat interface
      $.ajax({
        url: "load_chat_interface.php", // You'll need to create this file  
        method: "POST",
        data: {
          user_id: userId,
          admin_id: adminId
        },
        success: function(response) {
          $("#chat-panel").html(response);
          scrollToBottom();
          
          // Start refreshing messages for this user
          messagesRefreshInterval = setInterval(() => {
            loadMessages(userId, adminId);
          }, 3000);
        },
        error: function() {
          alert("Error loading conversation.");
        }
      });
    }

    function loadMessages(userId, adminId) {
      if (!userId || !adminId) return;

      const chatBox = document.getElementById("chat-box");
      if (!chatBox) return;

      const prevScrollHeight = chatBox.scrollHeight;
      const prevScrollTop = chatBox.scrollTop;
      
      $.ajax({
        url: "adminfetch.php",
        method: "POST",
        data: {
          user_id: userId,
          admin_id: adminId
        },
        success: function(data) {
          $("#messages-container").html(data);
          
          // Maintain scroll position or scroll to bottom if near bottom
          const newScrollHeight = chatBox.scrollHeight;
          const wasNearBottom = (prevScrollHeight - prevScrollTop) <= (chatBox.clientHeight + 100);
          
          if (wasNearBottom || newScrollHeight > prevScrollHeight) {
            scrollToBottom();
          } else {
            chatBox.scrollTop = prevScrollTop;
          }
        },
        error: function() {
          console.log("Error loading messages");
        }
      });
    }

    function scrollToBottom() {
      const chatBox = document.getElementById("chat-box");
      if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
      }
    }

    // Initialize user list
    fetchUserListWithNotifications();

    // Refresh user list every 5 seconds
    setInterval(fetchUserListWithNotifications, 5000);

    // Admin selector change
    $("#adminSelector").on("change", function() {
      const newAdminId = parseInt($(this).val());
      currentAdminId = newAdminId;
      
      // Clear current chat
      currentUserId = null;
      if (messagesRefreshInterval) {
        clearInterval(messagesRefreshInterval);
      }
      
      // Show empty state
      $("#chat-panel").html(`
        <div class="flex-1 flex items-center justify-center bg-gray-50">
          <div class="text-center text-gray-500">
            <div class="w-16 h-16 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
            </div>
            <h3 class="text-lg font-medium mb-2">Select a conversation</h3>
            <p>Choose a user from the sidebar to start messaging</p>
            <p class="text-xs mt-2 text-gray-400">Admin changed - select user to continue</p>
          </div>
        </div>
      `);
      
      // Refresh user list for new admin
      fetchUserListWithNotifications();
    });

    // User selection click handler (delegated event)
    $(document).on("click", ".user-item", function(e) {
      e.preventDefault();
      
      const userId = parseInt($(this).data("user-id"));
      const adminId = currentAdminId;

      // Remove previous selection
      $(".user-item").removeClass("bg-orange-50 border-l-4 border-l-orange-500");
      
      // Highlight current selection
      $(this).addClass("bg-orange-50 border-l-4 border-l-orange-500");

      // Load chat for this user
      loadUserChat(userId, adminId);
    });

    // Search users
    $(document).on("input", "#userSearch", function() {
      const searchTerm = $(this).val().toLowerCase();
      $(".user-item").each(function() {
        const userName = $(this).find("h3").text().toLowerCase();
        const userEmail = $(this).find("p").text().toLowerCase();

        if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    // Handle message sending (delegated event)
    $(document).on("submit", "#messageForm", function(e) {
      e.preventDefault();
      
      const msg = $("#messageInput").val().trim();
      const receiverId = $("#receiver_id").val();
      const btn = $(this).find("button[type='submit']");

      if (!msg || !receiverId) return;

      btn.prop("disabled", true).text("Sending...");
      
      $.ajax({
        url: "send_admin_reply.php",
        method: "POST",
        data: {
          message: msg,
          receiver_id: receiverId,
          admin_id: currentAdminId
        },
        success: function(response) {
          $("#messageInput").val("");
          loadMessages(currentUserId, currentAdminId);
          scrollToBottom();
        },
        error: function() {
          alert("Error sending message");
        },
        complete: function() {
          btn.prop("disabled", false).text("Send as Admin");
        }
      });
    });

    // Handle Enter key for message input (delegated event)
    $(document).on("keydown", "#messageInput", function(e) {
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        $("#messageForm").submit();
      }
    });

    // Handle scroll button (delegated event)
    $(document).on("click", "#scrollBtn", function() {
      scrollToBottom();
    });

    // Handle chat scroll for scroll button visibility (delegated event)  
    $(document).on("scroll", "#chat-box", function() {
      const chatBox = this;
      const scrollBtn = $("#scrollBtn");
      if (scrollBtn.length) {
        const nearBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 100;
        scrollBtn.toggleClass("hidden", nearBottom);
      }
    });

    // Load initial chat if user_id is in URL
    <?php if ($selected_user_id): ?>
      $(document).ready(function() {
        loadUserChat(<?= $selected_user_id ?>, <?= $admin_id ?>);
        $(`.user-item[data-user-id="<?= $selected_user_id ?>"]`).addClass("bg-orange-50 border-l-4 border-l-orange-500");
      });
    <?php endif; ?>

  </script>

</body>

</html>