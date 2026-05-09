<?php
session_start();
include 'includes/db_connect.php';

// 1. Security Check: Role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') { 
    header("Location: index.php"); 
    exit(); 
}

// 2. Security Check: Listener ID
if (!isset($_GET['listener_id']) || !is_numeric($_GET['listener_id'])) { 
    echo "<script>alert('Invalid Listener ID.'); window.location.href='student_home.php';</script>";
    exit(); 
}

$listener_id = intval($_GET['listener_id']);

// 3. Fetch Listener Profile (Using Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM listener_profiles WHERE user_id = ?");
$stmt->bind_param("i", $listener_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Listener profile not found.'); window.location.href='student_home.php';</script>";
    exit();
}

$listener = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Chat - PeerLink</title>
    <link rel="stylesheet" href="css/style.css?v=102">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .form-card { max-width: 600px; margin: 30px auto; border-top: 5px solid #003366; }
        .input-group { margin-bottom: 20px; }
        .input-label { display: block; font-weight: bold; margin-bottom: 8px; color: #003366; }
        .input-field { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 16px; }
        .btn-send { width: 100%; padding: 15px; background: #003366; color: white; border: none; border-radius: 25px; font-weight: bold; font-size: 18px; cursor: pointer; transition: 0.3s; }
        .btn-send:hover { background: #002244; transform: translateY(-2px); }
    </style>
</head>
<body class="dashboard-page">

    <div class="top-bar">
        <div style="display:flex; align-items:center;">
            <div class="mobile-toggle" onclick="toggleSidebar()">☰</div>
            <div class="top-title">PeerLink</div>
        </div>
        <div class="settings-container"><div class="settings-icon">⚙️</div></div>
    </div>

    <div class="dashboard-wrapper">
        <div class="sidebar" id="mySidebar">
            <div class="sidebar-header">Main Navigation</div>
            <a href="student_home.php"><button class="nav-btn"><span>ℹ️</span> About</button></a>
            <a href="student_home.php"><button class="nav-btn active"><span>👥</span> Listener Booking</button></a>
            <a href="student_home.php"><button class="nav-btn"><span>🤖</span> Ask Us</button></a>
            <a href="student_home.php"><button class="nav-btn"><span>🌍</span> Socials</button></a>
            <a href="logout.php" class="logout-btn"><span>🚪</span> LOGOUT</a>
        </div>

        <div class="main-content">
            <a href="booking_form.php?listener_id=<?php echo $listener_id; ?>" style="color: #003366; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 20px;">← Back to Profile</a>

            <div class="form-card">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h2 style="margin: 0; color: #003366; font-family: 'Georgia', serif;">Request a Session</h2>
                    <p style="color: #666; margin-top: 10px;">with <strong><?php echo htmlspecialchars($listener['alias']); ?></strong></p>
                </div>

                <form action="submit_request.php" method="POST">
                    <input type="hidden" name="listener_id" value="<?php echo $listener_id; ?>">
                    <div class="input-group">
                        <label class="input-label">Your Name (Alias or Real Name)</label>
                        <input type="text" name="form_name" class="input-field" placeholder="Ex: Student A" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Grade & Section</label>
                        <input type="text" name="form_grade_section" class="input-field" placeholder="Ex: Grade 10 - Einstein" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label">What would you like to talk about?</label>
                        <input type="text" name="form_topic" class="input-field" placeholder="Ex: Academic Stress, Anxiety..." required>
                    </div>
                    <button type="submit" name="send_request" class="btn-send">Send Request</button>
                </form>
            </div>
        </div>
    </div>

    <script>function toggleSidebar(){document.getElementById("mySidebar").classList.toggle("active");}</script>
</body>
</html>