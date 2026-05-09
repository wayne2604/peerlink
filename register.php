<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PeerLink</title>
    
    <link rel="stylesheet" href="css/style.css?v=50">

    <style>
        /* INTERNAL STYLES (Guarantees Design Loads) */
        body {
            background-color: #003366; /* Navy Blue */
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Allow scrolling on small screens */
            margin: 0;
            padding: 20px 0; /* Add top/bottom space for mobile */
        }
        
        .login-card {
            background: white;
            padding: 30px 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 450px; /* Slightly wider for register form */
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border-top: 8px solid #ffcc00; /* Gold Accent */
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #003366;
            margin-bottom: 10px;
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
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 25px;
            font-size: 15px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
            background: #fff;
        }

        .form-input:focus, .form-select:focus {
            border-color: #003366;
            box-shadow: 0 0 5px rgba(0, 51, 102, 0.3);
        }

        .btn-register {
            width: 100%;
            padding: 12px;
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
        }

        .btn-register:hover {
            background-color: #002244;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .links {
            margin-top: 20px;
            font-size: 13px;
            color: #555;
        }
        .links a {
            color: #003366;
            text-decoration: none;
            font-weight: bold;
        }
        .links a:hover { text-decoration: underline; }

        /* Error Message Box */
        .error-msg {
            background: #ffebee; color: #c62828; padding: 10px;
            border-radius: 10px; margin-bottom: 15px; font-size: 13px;
            border: 1px solid #ef9a9a;
        }
        /* Success Message Box */
        .success-msg {
            background: #d4edda; color: #155724; padding: 10px;
            border-radius: 10px; margin-bottom: 15px; font-size: 13px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <img src="images/peerlink_navigator.png" class="logo" alt="Logo" onerror="this.src='images/default_avatar.png'">
        <div class="login-title">PeerLink</div>
        <div class="login-subtitle">Create your account</div>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">
                <?php 
                    if ($_GET['error'] == "empty_fields") echo "Please fill in all fields.";
                    elseif ($_GET['error'] == "invalid_email") echo "Invalid email format.";
                    elseif ($_GET['error'] == "password_mismatch") echo "Passwords do not match.";
                    elseif ($_GET['error'] == "email_taken") echo "Email already registered.";
                    else echo "Registration failed. Try again.";
                ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-msg">Registration successful! You can now login.</div>
        <?php endif; ?>

        <form action="register_process.php" method="POST">
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="real_name" class="form-input" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="listener">Peer Listener</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Grade & Section</label>
                <input type="text" name="grade_section" class="form-input" placeholder="Ex: Grade 10 - Einstein">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Create a password" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-input" placeholder="Repeat password" required>
            </div>

            <button type="submit" name="register" class="btn-register">Register</button>
        </form>

        <div class="links">
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>

</body>
</html>