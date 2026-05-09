<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        // Update status to 'accepted'
        $sql = "UPDATE bookings SET status='accepted' WHERE id=$booking_id";
        if ($conn->query($sql) === TRUE) {
            // UPDATED REDIRECT: Points to communication_room.php
            header("Location: communication_room.php?booking_id=$booking_id");
            exit();
        }
    } elseif ($action == 'reject') {
        // Update status to 'rejected'
        $sql = "UPDATE bookings SET status='rejected' WHERE id=$booking_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: listener_dashboard.php");
            exit();
        }
    }
}
?>