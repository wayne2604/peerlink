<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/db_connect.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: index.php?error=empty_fields");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password, role, is_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify Hash OR Plain Text (Temporary fix for old accounts)
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            
            // Check Verification
            if ($row['role'] === 'listener' && $row['is_verified'] == 0) {
                header("Location: index.php?error=not_verified");
                exit();
            }

            // Success
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $email;

            if ($row['role'] === 'student') header("Location: student_home.php");
            elseif ($row['role'] === 'listener') header("Location: listener_dashboard.php");
            elseif ($row['role'] === 'admin') header("Location: admin_dashboard.php");
            exit();

        } else {
            header("Location: index.php?error=wrong_password");
            exit();
        }
    } else {
        header("Location: index.php?error=user_not_found");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>