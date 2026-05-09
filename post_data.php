<?php
session_start();
include 'includes/db_connect.php';

// 1. Enable Error Reporting (For Debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Check Authentication
if (!isset($_SESSION['user_id'])) {
    echo "Error: Not logged in";
    exit();
}

// 3. Check Input Data
if (isset($_POST['booking_id']) && isset($_POST['message'])) {
    
    // Force integer conversion to prevent SQL errors
    $booking_id = intval($_POST['booking_id']); 
    $sender_id = intval($_SESSION['user_id']);
    $message = trim($_POST['message']);

    // 4. Validate Data
    if ($booking_id <= 0) {
        echo "Error: Invalid Booking ID";
        exit();
    }

    if (!empty($message)) {
        // Escape special characters to prevent SQL Injection
        $message_safe = $conn->real_escape_string($message);

        // 5. Insert Query
        $sql = "INSERT INTO messages (booking_id, sender_id, message) VALUES ($booking_id, $sender_id, '$message_safe')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Sent";
        } else {
            echo "Database Error: " . $conn->error;
        }
    } else {
        echo "Error: Empty message";
    }
} else {
    echo "Error: Missing POST data";
}
?>