<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - PeerLink</title>
    <link rel="stylesheet" href="css/style.css?v=50">
    </head>
<body style="background-color: #003366; font-family: 'Arial', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh;">

    <div style="background: white; padding: 40px; border-radius: 20px; width: 100%; max-width: 400px; text-align: center;">
        <h2 style="color: #003366;">Reset Password</h2>
        <p style="color: #666;">Enter your email to receive a verification code.</p>

        <?php if (isset($_GET['error'])): ?>
            <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="send_otp.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;">
            
            <button type="submit" name="send_code" 
                    style="width: 100%; padding: 10px; background: #003366; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Send Code
            </button>
        </form>
        <br>
        <a href="index.php" style="text-decoration: none; color: #003366;">Back to Login</a>
    </div>

</body>
</html>