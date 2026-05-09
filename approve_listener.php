<?php
session_start();
include 'includes/db_connect.php';

// Check if Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // 1. Get User Email first (to send the notification)
    $query = "SELECT email, real_name FROM users WHERE id = $user_id";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();
    $email_to = $user['email'];
    $name = $user['real_name'];

    // 2. Update Database to VERIFIED (1)
    $sql = "UPDATE users SET is_verified = 1 WHERE id = $user_id";

    if ($conn->query($sql) === TRUE) {
        
        // 3. Send Email
        $subject = "PeerLink Application Approved!";
        $message = "Hello $name,\n\nCongratulations! Your application to be a Peer Listener has been approved by the Guidance Counselor.\n\nYou can now log in to your dashboard here: http://your-website-link.com\n\nWelcome to the team!";
        $headers = "From: admin@peerlink.com";

        // The @ symbol suppresses errors if XAMPP mail isn't configured
        $mail_sent = @mail($email_to, $subject, $message, $headers);

        echo "<script>
                alert('User Approved! Email notification sent to $email_to.');
                window.location='admin_dashboard.php';
              </script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>