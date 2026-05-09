<?php
session_start();
include 'includes/db_connect.php';

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['booking_id'])) exit();

$booking_id = intval($_GET['booking_id']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// FIXED: Changed 'ORDER BY timestamp' to 'ORDER BY created_at'
$sql = "SELECT * FROM messages WHERE booking_id = $booking_id ORDER BY created_at ASC";
$result = $conn->query($sql);

if (!$result) {
    die("<div style='color:red; text-align:center; padding:10px;'>
            <strong>Database Error:</strong> " . $conn->error . "
         </div>");
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $class = ($row['sender_id'] == $user_id) ? "my-message" : "their-message";
        $color = ($row['sender_id'] == $user_id) ? "bubble-blue" : "bubble-gray";
        
        echo '<div class="message-row ' . $class . '">
                <div class="chat-bubble ' . $color . '">' . htmlspecialchars($row['message']) . '</div>
              </div>';
    }
} else {
    echo '<div style="text-align:center; color:#999; margin-top:20px;">No messages yet. Start chatting!</div>';
}
?>