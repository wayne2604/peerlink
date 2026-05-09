<?php
session_start();
include 'includes/db_connect.php';

// STEP 1: Check if Email Exists
if (isset($_POST['check_email'])) {
    $email = $_POST['email'];
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        // Email found! Show the "New Password" Form with the NEW DESIGN
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Reset Password</title>
            <link rel="stylesheet" href="css/style.css?v=7">
        </head>
        <body class="auth-page">

            <div class="top-bar">
                <div class="top-title">PeerLink</div>
            </div>

            <div class="auth-container">
                <h2 class="auth-header">Reset Password</h2>
                <p style="color: #666; margin-bottom: 20px;">Account found for:<br><strong><?php echo $email; ?></strong></p>
                
                <form action="reset_password_logic.php" method="POST">
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    
                    <label>New Password</label>
                    <input type="password" name="new_pass" placeholder="Create new password" required>
                    
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_pass" placeholder="Confirm new password" required>
                    
                    <button type="submit" name="update_pass" class="btn-primary">UPDATE PASSWORD</button>
                </form>

                <div style="margin-top: 20px;">
                    <a href="forgot_password.php">Cancel</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Email not found!'); window.location='forgot_password.php';</script>";
    }
}

// STEP 2: Update the Password in Database
if (isset($_POST['update_pass'])) {
    $email = $_POST['email'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($new_pass === $confirm_pass) {
        // NOTE: In a real app, use password_hash($new_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='$new_pass' WHERE email='$email'";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Password updated successfully! You can now login.'); window.location='index.php';</script>";
        } else {
            echo "Error updating: " . $conn->error;
        }
    } else {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    }
}
?>