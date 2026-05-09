<?php
// Get the logged-in Listener's ID
$listener_id = $_SESSION['user_id'];

// Fetch only 'pending' requests for this listener
$sql = "SELECT * FROM bookings WHERE listener_id = $listener_id AND status = 'pending'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // This generates the "Card" HTML seen in your design [cite: 258]
        echo '<div class="request-card">';
        echo '<h3>Client Request</h3>';
        echo '<p><strong>Name:</strong> ' . $row['real_name'] . '</p>';
        echo '<p><strong>Section:</strong> ' . $row['grade_section'] . '</p>';
        echo '<p><strong>Topic:</strong> ' . $row['topic'] . '</p>';
        
        // The Accept/Reject buttons
        echo '<form action="process_request.php" method="POST">';
        echo '<input type="hidden" name="booking_id" value="' . $row['id'] . '">';
        echo '<button name="action" value="accept" class="btn-accept">ACCEPT</button>';
        echo '<button name="action" value="reject" class="btn-reject">REJECT</button>';
        echo '</form>';
        echo '</div>';
    }
} else {
    echo "No new requests.";
}
?>