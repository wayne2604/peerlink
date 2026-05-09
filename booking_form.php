<?php
session_start();
include 'includes/db_connect.php';

// 1. Security: Check Login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

// 2. Security: Validate ID
if (isset($_GET['listener_id']) && is_numeric($_GET['listener_id'])) {
    $listener_id = intval($_GET['listener_id']);
    
    // Fetch Profile Data
    $sql = "SELECT * FROM listener_profiles WHERE user_id = $listener_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $listener = $result->fetch_assoc();
    } else {
        echo "<script>alert('Profile not found.'); window.location='student_home.php';</script>";
        exit();
    }
} else {
    header("Location: student_home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - <?php echo $listener['alias']; ?></title>
    <link rel="icon" type="image/png" href="images/peerlink_navigator.png">
    <link rel="stylesheet" href="css/style.css?v=16"> <style>
        /* FORMAL PROFILE CARD DESIGN */
        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0, 51, 102, 0.15);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 40px;
            border: 1px solid #e0e0e0;
        }
        .profile-details { flex: 1; font-size: 16px; line-height: 1.8; color: #333; }
        .detail-row { margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; display: flex; }
        .label { font-weight: bold; color: #003366; width: 180px; min-width: 180px; font-family: 'Georgia', serif; }
        .value { font-family: 'Arial', sans-serif; color: #555; }
        .profile-image-large {
            width: 220px; height: 220px; border-radius: 50%; object-fit: cover;
            border: 6px solid #f0f0f0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-action-container { text-align: center; margin-top: 40px; }
        .btn-formal {
            background-color: #003366; color: white; padding: 15px 50px; border-radius: 30px;
            font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
            border: none; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: all 0.3s ease;
        }
        .btn-formal:hover { background-color: #002244; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.3); }
        .back-link { display: block; margin-bottom: 20px; color: #666; font-size: 14px; }
        .back-link:hover { color: #003366; }
    </style>
</head>
<body class="dashboard-page">

    <div class="top-bar">
        <div class="top-title">PeerLink</div>
    </div>

    <div class="dashboard-wrapper">
        <div class="sidebar">
            <div class="sidebar-header"><span class="sidebar-icon">≡</span> Peer Tabs</div>
            <a href="student_home.php"><button class="nav-btn">About</button></a>
            <a href="student_home.php"><button class="nav-btn active">Listener Booking</button></a>
            <a href="student_home.php"><button class="nav-btn">Ask Us</button></a>
            <a href="student_home.php"><button class="nav-btn">Socials</button></a>
        </div>

        <div class="main-content">
            <a href="student_home.php" class="back-link">← Back to Dashboard</a>
            <div class="page-title">Peer Profile</div>

            <div class="profile-card">
                <div class="profile-details">
                    <div class="detail-row"><span class="label">Name (Alias):</span> <span class="value"><?php echo htmlspecialchars($listener['alias']); ?></span></div>
                    <div class="detail-row"><span class="label">Personality:</span> <span class="value"><?php echo htmlspecialchars($listener['personality']); ?></span></div>
                    <div class="detail-row"><span class="label">Approach:</span> <span class="value"><?php echo htmlspecialchars($listener['approach']); ?></span></div>
                    <div class="detail-row"><span class="label">Religion:</span> <span class="value"><?php echo htmlspecialchars($listener['religion']); ?></span></div>
                    <div class="detail-row"><span class="label">Belief:</span> <span class="value"><?php echo htmlspecialchars($listener['belief']); ?></span></div>
                    <div class="detail-row"><span class="label">Focus:</span> <span class="value"><?php echo htmlspecialchars($listener['focus']); ?></span></div>
                    <div class="detail-row"><span class="label">Goal:</span> <span class="value"><?php echo htmlspecialchars($listener['goal']); ?></span></div>
                    <div class="detail-row"><span class="label">Favorite Reminder:</span> <span class="value"><?php echo htmlspecialchars($listener['reminder']); ?></span></div>
                    <div class="detail-row"><span class="label">Hobbies:</span> <span class="value"><?php echo htmlspecialchars($listener['hobbies']); ?></span></div>
                </div>

                <?php 
                    // FIXED: Using 'avatar' column instead of avatar_image
                    $avatar = !empty($listener['avatar']) ? $listener['avatar'] : 'default_avatar.png';
                ?>
                <img src="images/<?php echo $avatar; ?>" class="profile-image-large" alt="Profile Picture">
            </div>

            <div class="btn-action-container">
                <a href="client_form.php?listener_id=<?php echo $listener_id; ?>">
                    <button class="btn-formal">Request to Chat</button>
                </a>
            </div>

        </div>
    </div>
</body>
</html>