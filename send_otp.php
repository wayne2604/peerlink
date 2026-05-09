<?php
session_start();
include 'includes/db_connect.php';

// LOAD PHPMAILER MANUALLY
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['send_code'])) {
    $email = $_POST['email'];

    // 1. Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        
        // 2. Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // 3. Save OTP to Database
        $update = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $update->bind_param("ss", $otp, $email);
        $update->execute();

        // 4. Send Email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP Settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'peerlink7@gmail.com'; // <--- CHANGE THIS
            $mail->Password   = 'bwlz dbtj zonl mwge';       // <--- CHANGE THIS (The 16 char code)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email Content
            $mail->setFrom('no-reply@peerlink.com', 'PeerLink Security');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your PeerLink Reset Code';
            $mail->Body    = "<h3>Password Reset Request</h3>
                              <p>Your verification code is: <b style='font-size: 20px;'>$otp</b></p>
                              <p>Enter this code to reset your password.</p>";

            $mail->send();

            // 5. Redirect to Verification Page
            $_SESSION['reset_email'] = $email; // Save email for next step
            header("Location: verify_otp.php");
            exit();

        } catch (Exception $e) {
            header("Location: forgot_password.php?error=Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            exit();
        }

    } else {
        header("Location: forgot_password.php?error=Email not found!");
        exit();
    }
}
?>