<?php
session_start();
// If user is already logged in, redirect them
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'student') header("Location: student_home.php");
    elseif ($_SESSION['role'] == 'listener') header("Location: listener_dashboard.php");
    elseif ($_SESSION['role'] == 'admin') header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PeerLink</title>

    <link rel="icon" type="image/png" href="images/peerlink_navigator.png">
    
    <style>
        /* PRO-LEVEL LOGIN STYLES */
        body {
            background-color: #003366; /* Navy Blue Background */
            font-family: 'Segoe UI', 'Roboto', 'Helvetica', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px; /* Padding for mobile */
        }
        
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border-top: 6px solid #ffcc00; /* Gold Accent */
        }

        .logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #003366;
            margin-bottom: 15px;
            background: #eee;
        }

        .login-title {
            font-family: 'Georgia', serif;
            color: #003366;
            font-size: 28px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .login-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 15px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
            background-color: #f9f9f9;
        }

        .form-input:focus {
            border-color: #003366;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(0, 51, 102, 0.2);
        }

        .btn-login:hover {
            background-color: #002244;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 51, 102, 0.3);
        }

        /* Error Message Style */
        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #ef9a9a;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Links Section */
        .links {
            margin-top: 25px;
            font-size: 14px;
            color: #555;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .links a {
            color: #003366;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }
        .links a:hover {
            color: #004080;
            text-decoration: underline;
        }
        
        .forgot-link {
            display: block;
            margin-top: 15px;
            font-size: 13px;
            color: #777 !important;
            font-weight: normal !important;
        }
        .forgot-link:hover {
            color: #333 !important;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <img src="images/peerlink_navigator.png" class="logo" alt="Logo" onerror="this.src='images/default_avatar.png'">
        
        <div class="login-title">PeerLink</div>
        <div class="login-subtitle">Sign in to your account</div>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">
                <span>⚠️</span>
                <?php 
                    if ($_GET['error'] == "empty_fields") echo "Please fill in all fields.";
                    elseif ($_GET['error'] == "wrong_password") echo "Incorrect password.";
                    elseif ($_GET['error'] == "user_not_found") echo "No account found with this email.";
                    elseif ($_GET['error'] == "not_verified") echo "Your account is pending approval.";
                    else echo "Login failed. Please try again.";
                ?>
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" class="form-input" placeholder="Email Address" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-input" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>
            
            <a href="forgot_password.php" class="forgot-link">Forgot your password?</a>
        </form>

        <div class="links">
            <p>Don't have an account? <br> <a href="register.php">Create New Account</a></p>
        </div>
    </div>

</body>
</html>