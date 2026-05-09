<?php
// 1. Enable Error Reporting (To see issues instead of a white screen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'includes/db_connect.php';

// 2. Check if form is submitted
if (isset($_POST['register'])) {
    
    // 3. Get and Sanitize Input
    $real_name = trim($_POST['real_name']);
    $email = trim($_POST['email']);
    $role = $_POST['role']; // 'student' or 'listener'
    $grade_section = trim($_POST['grade_section']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 4. Basic Validation
    if (empty($real_name) || empty($email) || empty($password) || empty($role)) {
        header("Location: register.php?error=empty_fields");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=invalid_email");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }

    // 5. Check if Email Already Exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        header("Location: register.php?error=email_taken");
        exit();
    }
    $check_stmt->close();

    // 6. Secure Password Hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 7. Determine Verification Status
    // Students are auto-verified (1). Listeners must be approved by Admin (0).
    $is_verified = ($role === 'student') ? 1 : 0;

    // 8. Insert User into Database
    $stmt = $conn->prepare("INSERT INTO users (real_name, email, password, role, grade_section, is_verified) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("SQL Error (Users): " . $conn->error);
    }
    $stmt->bind_param("sssssi", $real_name, $email, $hashed_password, $role, $grade_section, $is_verified);

    if ($stmt->execute()) {
        $new_user_id = $stmt->insert_id; // Get the ID of the new user

        // 9. CRITICAL: Create Profile Entry for Listeners
        // If we don't do this, the dashboard will crash looking for profile data.
        if ($role === 'listener') {
            $prof_stmt = $conn->prepare("INSERT INTO listener_profiles (user_id) VALUES (?)");
            if (!$prof_stmt) {
                die("SQL Error (Profile): " . $conn->error);
            }
            $prof_stmt->bind_param("i", $new_user_id);
            $prof_stmt->execute();
            $prof_stmt->close();
        }

        // 10. Success Redirect
        header("Location: index.php?success=registered");
        exit();

    } else {
        // Database Insert Failed
        header("Location: register.php?error=sql_error");
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    // Accessing file directly without submitting form
    header("Location: register.php");
    exit();
}
?>