<?php
session_start();
include 'includes/db_connect.php';

// 1. Check if the user clicked the button
if (isset($_POST['send_request'])) {
    
    // 2. Get the current student's ID (from session)
    if (!isset($_SESSION['user_id'])) {
        die("Error: You must be logged in.");
    }
    $student_id = $_SESSION['user_id'];

    // 3. Get the form data
    $listener_id = $_POST['listener_id'];
    $name = $_POST['form_name'];
    $grade_section = $_POST['form_grade_section'];
    $topic = $_POST['form_topic'];

    // Validate Inputs
    if (empty($listener_id) || empty($topic)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // --- 4. NEW: ANTI-DUPLICATE CHECK ---
    // Check if this student ALREADY has a "pending" request for THIS listener
    $check_sql = "SELECT id FROM bookings 
                  WHERE student_id = '$student_id' 
                  AND listener_id = '$listener_id' 
                  AND status = 'pending'";
    
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // DUPLICATE FOUND: Stop the process
        echo "<script>
                alert('Request Failed: You already have a PENDING request with this Peer Listener. Please wait for them to respond before sending another.');
                window.location.href = 'student_home.php';
              </script>";
        exit();
    }
    // ------------------------------------

    // 5. Insert into Database (Only if no duplicate found)
    $sql = "INSERT INTO bookings (student_id, listener_id, form_name, form_grade_section, form_topic, status) 
            VALUES ('$student_id', '$listener_id', '$name', '$grade_section', '$topic', 'pending')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Request Sent Successfully! The Peer Listener will be notified.');
                window.location.href = 'student_home.php'; 
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // If they try to access this file directly without submitting
    header("Location: student_home.php");
    exit();
}
?>