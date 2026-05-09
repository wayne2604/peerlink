<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
    <style>
        body { background-color: #003366; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card { background: white; padding: 30px; border-radius: 15px; text-align: center; width: 350px; }
        input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<div class="card">
    <h3>Enter Verification Code</h3>
    <p>We sent a code to <b><?php echo $email; ?></b></p>

    <form action="" method="POST">
        <input type="text" name="otp_code" placeholder="Enter 6-digit Code" required>
        <input type="password" name="new_pass" placeholder="New Password" required>
        <input type="password" name="confirm_pass" placeholder="Confirm Password" required>
        <button type="submit" name="reset_now">Reset Password</button>
    </form>
</div>

</body>
</html>

<?php
if (isset($_POST['reset_now'])) {
    $otp_input = $_POST['otp_code'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // 1. Verify Code in Database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND reset_token = ?");
    $stmt->bind_param("ss", $email, $otp_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($new_pass === $confirm_pass) {
            
            // 2. Update Password & Clear Token
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE email = ?");
            $update->bind_param("ss", $hashed_pass, $email);
            
            if ($update->execute()) {
                session_destroy();
                echo "<script>alert('Password Changed Successfully!'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Database Error!');</script>";
            }

        } else {
            echo "<script>alert('Passwords do not match!');</script>";
        }
    } else {
        echo "<script>alert('Invalid Code! Please check your email.');</script>";
    }
}
?>