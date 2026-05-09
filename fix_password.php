<?php
include 'includes/db_connect.php';

// CHANGE THESE TWO LINES TO YOUR DESIRED LOGIN
$email_to_fix = "rmanubag308@gmail.com"; // Put the email you are trying to use
$new_password = "password123";           // Put the password you WANT to use

// 1. Generate the secure hash
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// 2. Update the database
$sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email_to_fix'";

if ($conn->query($sql) === TRUE) {
    echo "<h1>Success! ✅</h1>";
    echo "<p>Password for <b>$email_to_fix</b> has been updated.</p>";
    echo "<p>You can now log in with: <b>$new_password</b></p>";
    echo "<br><a href='index.php'>Go to Login</a>";
} else {
    echo "Error updating record: " . $conn->error;
}
?>