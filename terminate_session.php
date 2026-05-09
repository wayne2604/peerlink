<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    $new_status = 'completed'; // Default
    if ($action == 'report') {
        $new_status = 'reported';
    }

    $sql = "UPDATE bookings SET status='$new_status' WHERE id=$booking_id";
    
    if ($conn->query($sql) === TRUE) {
        // Free up the listener and send them back to dashboard
        echo "<script>alert('Session Ended.'); window.location.href='listener_dashboard.php';</script>";
    }
}
?>