<?php
session_start();
include 'includes/db_connect.php';

// 1. Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// 2. Report Modal Logic
if (isset($_POST['dismiss_report'])) {
    $report_booking_id = intval($_POST['booking_id']);
    $conn->query("UPDATE bookings SET viewed_by_student = 1 WHERE id = $report_booking_id AND student_id = $student_id");
    header("Location: student_home.php");
    exit();
}

$latest_booking_sql = "SELECT * FROM bookings WHERE student_id = $student_id ORDER BY id DESC LIMIT 1";
$res_status = $conn->query($latest_booking_sql);
$latest_booking = $res_status->fetch_assoc();

$show_report = false;
if ($latest_booking && $latest_booking['status'] == 'reported' && $latest_booking['viewed_by_student'] == 0) {
    $show_report = true;
}

// 3. Active Chat Check (FIXED: Checks 'bookings' table for 'accepted' status)
$active_chat_sql = "SELECT * FROM bookings 
                    WHERE student_id = $student_id 
                    AND status = 'accepted' 
                    LIMIT 1";
$active_chat_res = $conn->query($active_chat_sql);

// 4. Fetch Listeners
$listeners_sql = "SELECT users.id, listener_profiles.alias, listener_profiles.specialty, listener_profiles.avatar, users.birthdate 
                  FROM users 
                  JOIN listener_profiles ON users.id = listener_profiles.user_id 
                  WHERE users.role = 'listener' AND users.is_verified = 1";
$listeners = $conn->query($listeners_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PeerLink Dashboard</title>
    <link rel="icon" type="image/png" href="images/peerlink_navigator.png">
    <link rel="stylesheet" href="css/style.css?v=100"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 31, 63, 0.85);
            display: flex; justify-content: center; align-items: center; z-index: 9999;
        }
        .modal-box {
            background: white; padding: 40px; border-radius: 12px;
            max-width: 500px; text-align: center;
            border-top: 8px solid #ffcc00;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        .btn-modal {
            padding: 12px 30px; background-color: #003366; color: white;
            border: none; border-radius: 8px; cursor: pointer;
            font-weight: bold; font-size: 16px; transition: 0.2s;
        }
        .btn-modal:hover { background-color: #002244; transform: translateY(-2px); }
    </style>
</head>
<body class="dashboard-page">

    <div class="top-bar">
        <div style="display:flex; align-items:center;">
            <div class="mobile-toggle" onclick="toggleSidebar()">☰</div>
            <div class="top-title">Hello Client!</div> 
        </div>
        
        <div class="settings-container">
            <div class="settings-icon" onclick="toggleSettings()">⚙️</div>
            <div id="settingsDropdown" class="dropdown-menu">
                <a href="#" onclick="toggleTheme(); return false;">🌙 Switch Theme</a>
                <a href="logout.php" style="color: #dc3545;">🚪 Logout</a>
            </div>
        </div>
    </div>

    <div class="dashboard-wrapper">
        
        <div class="sidebar" id="mySidebar">
            <div class="sidebar-header">Main Navigation</div>
            
            <button class="nav-btn active" onclick="openTab('about', this); toggleSidebar()">
                <span>ℹ️</span> About
            </button>
            <button class="nav-btn" onclick="openTab('booking', this); toggleSidebar()">
                <span>👥</span> Listener Booking
            </button>
            <button class="nav-btn" onclick="openTab('ask', this); toggleSidebar()">
                <span>🤖</span> Ask Us
            </button>
            <button class="nav-btn" onclick="openTab('socials', this); toggleSidebar()">
                <span>🌍</span> Socials
            </button>
            
            <a href="logout.php" class="logout-btn">
                <span>🚪</span> LOGOUT
            </a>
        </div>

        <div class="main-content">
            
            <?php 
            // FIXED: Display Logic for Active Chat
            if ($active_chat_res->num_rows > 0) { 
                $chat = $active_chat_res->fetch_assoc(); 
            ?>
                <div style="background: white; border-left: 6px solid #ffcc00; padding: 25px; margin-bottom: 30px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div>
                        <h2 style="margin: 0; color: #003366; font-size: 20px;">Session Active!</h2>
                        <p style="margin: 5px 0 0 0; color: #555;">Your Peer Listener is ready to chat.</p>
                    </div>
                    <a href="communication_room.php?booking_id=<?php echo $chat['id']; ?>" 
                       style="background-color: #003366; color: white; padding: 10px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; transition: 0.2s;">
                        RETURN TO CHAT 💬
                    </a>
                </div>
            <?php } ?>

            <div id="about" class="tab-content active" style="text-align: center;">
                <h1 style="font-family: 'Georgia', serif; font-size: 48px; color: #004080; margin-bottom: 10px;">PeerLink</h1>
                <div style="background: rgba(255,255,255,0.8); padding: 25px; border-radius: 12px; display: inline-block; max-width: 800px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <h2 style="font-size: 16px; letter-spacing: 1px; line-height: 1.6; color: #444; margin: 0;">
                        PEERLINK IS A WEBSITE IN COLLABORATION WITH<br>PEER HEALTH NAVIGATORS NETWORK (PHNN)<br>A dedicated group of students committed to supporting their fellow students through empathy, active listening, and guidance. They promote mental well-being, inclusivity, and a positive school environment.
                    </h2>
                </div>
                <div style="margin-top: 40px;">
                   <img src="images/peerlink_navigator.png" alt="PeerLink Logo" 
                        style="width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 4px solid #003366; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                </div>
            </div>

            <div id="booking" class="tab-content">
                <div class="page-title">Choose Your Peer</div>
                <div class="card-grid">
                    <?php if ($listeners->num_rows > 0) { while($row = $listeners->fetch_assoc()) { 
                        $avatar = !empty($row['avatar']) ? $row['avatar'] : 'default_avatar.png'; ?>
                        <div class="peer-card">
                            <img src="images/<?php echo $avatar; ?>" class="card-avatar">
                            <div>
                                <div class="card-alias"><?php echo $row['alias']; ?></div>
                                <div class="card-details">
                                    <strong>Specialty:</strong> <?php echo $row['specialty']; ?><br>
                                    <strong>Grade:</strong> 10 (Einstein)
                                </div>
                                <a href="booking_form.php?listener_id=<?php echo $row['id']; ?>" class="btn-action btn-primary">
                                    Select Peer
                                </a>
                            </div>
                        </div>
                    <?php } } else { echo "<p style='color:#666;'>No Peer Listeners available yet.</p>"; } ?>
                </div>
            </div>

            <div id="ask" class="tab-content">
                <div class="page-title">Ask Us Anything</div>
                <div class="faq-container">
                    <div class="faq-header">PeerLink Automated Support 🤖</div>
                    <div class="faq-body" id="faqBox">
                        <div class="message-row their-message">
                            <div class="chat-bubble bubble-gray">
                                Hello! Tap a question below for instant help. 👇
                            </div>
                        </div>
                    </div>
                    <div class="faq-footer">
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                            <button class="btn-action btn-primary" onclick="askBot('what')">What is PeerLink?</button>
                            <button class="btn-action btn-primary" onclick="askBot('how')">How to book?</button>
                            <button class="btn-action btn-primary" onclick="askBot('privacy')">Is it anonymous?</button>
                            <button class="btn-action btn-primary" onclick="askBot('urgent')" style="background-color: #dc3545;">Urgent Help</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="socials" class="tab-content">
                <div class="page-title">Partner Organizations</div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 20px;">
                    
                    <a href="https://www.facebook.com/profile.php?id=61565953794099" target="_blank" style="text-decoration: none; color: inherit;">
                        <div style="background: white; width: 700px; padding: 25px; border-radius: 12px; display: flex; align-items: center; gap: 25px; box-shadow: var(--shadow); border-left: 6px solid #003366; transition: transform 0.2s;" 
                             onmouseover="this.style.transform='translateY(-5px)'" 
                             onmouseout="this.style.transform='translateY(0)'">
                            <img src="images/znnhs.png" style="width: 80px;" onerror="this.src='images/default_avatar.png'">
                            <div>
                                <h2 style="margin: 0; color: #003366;">Zamboanga Del Norte National High School</h2>
                                <p style="margin: 5px 0 0 0; color: #666;">Turno, Dipolog City</p>
                            </div>
                        </div>
                    </a>

                    <a href="https://www.facebook.com/DOHgovPH" target="_blank" style="text-decoration: none; color: inherit;">
                        <div style="background: white; width: 700px; padding: 25px; border-radius: 12px; display: flex; align-items: center; gap: 25px; box-shadow: var(--shadow); border-left: 6px solid #d32f2f; transition: transform 0.2s;" 
                             onmouseover="this.style.transform='translateY(-5px)'" 
                             onmouseout="this.style.transform='translateY(0)'">
                            <img src="images/DOH_PH.png" style="width: 80px;" onerror="this.src='images/default_avatar.png'">
                            <div>
                                <h2 style="margin: 0; color: #d32f2f;">Department of Health</h2>
                                <p style="margin: 5px 0 0 0; color: #666;">Philippines</p>
                            </div>
                        </div>
                    </a>

                    <a href="https://www.facebook.com/profile.php?id=61577333962421" target="_blank" style="text-decoration: none; color: inherit;">
                        <div style="background: white; width: 700px; padding: 25px; border-radius: 12px; display: flex; align-items: center; gap: 25px; box-shadow: var(--shadow); border-left: 6px solid #239700; transition: transform 0.2s;" 
                             onmouseover="this.style.transform='translateY(-5px)'" 
                             onmouseout="this.style.transform='translateY(0)'">
                            <img src="images/peerlink_navigator.png" style="width: 80px;" onerror="this.src='images/default_avatar.png'">
                            <div>
                                <h2 style="margin: 0; color: #239700;">ZNNHS - Turno Peer Health Navigators Network</h2>
                                <p style="margin: 5px 0 0 0; color: #666;">Turno, Dipolog City</p>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <?php if ($show_report): ?>
    <div class="modal-overlay" id="reportModal">
        <div class="modal-box">
            <h1 style="color: #003366; margin-bottom: 15px;">⚠️ Important Notice</h1>
            <p style="color: #444; margin-bottom: 25px; line-height: 1.5;">
                This session has been flagged for further assistance. <br><br>
                <strong>Please proceed immediately to the Guidance Counselor's office.</strong>
            </p>
            <form method="POST">
                <input type="hidden" name="booking_id" value="<?php echo $latest_booking['id']; ?>">
                <button type="submit" name="dismiss_report" class="btn-modal">I Understand</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Tab Switching
        function openTab(tabName, btnElement) {
            $(".tab-content").removeClass("active");
            $(".nav-btn").removeClass("active");
            $("#" + tabName).addClass("active");
            $(btnElement).addClass("active");
        }

        // Mobile Sidebar Toggle
        function toggleSidebar() {
            $("#mySidebar").toggleClass("active");
        }

        // Settings Dropdown
        function toggleSettings() {
            $("#settingsDropdown").toggleClass("show-menu");
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.settings-icon')) {
                $("#settingsDropdown").removeClass("show-menu");
            }
        }

        // FAQ Bot Logic
        function askBot(topic) {
            var q = "", a = "";
            if (topic === 'what') { q = "What is PeerLink?"; a = "PeerLink is a safe space for students to connect with trained Peer Listeners."; } 
            else if (topic === 'how') { q = "How do I book?"; a = "Go to the 'Listener Booking' tab and click 'Select Peer'."; }
            else if (topic === 'privacy') { q = "Is this anonymous?"; a = "Yes! Your chats are private unless there is a risk of harm."; }
            else if (topic === 'urgent') { q = "I need urgent help."; a = "⚠️ Please contact the Guidance Counselor immediately."; }

            $("#faqBox").append('<div class="message-row my-message"><div class="chat-bubble bubble-blue">' + q + '</div></div>');
            scrollToBottom();
            
            setTimeout(function() {
                $("#faqBox").append('<div class="message-row their-message"><div class="chat-bubble bubble-gray">' + a + '</div></div>');
                scrollToBottom();
            }, 500);
        }

        function scrollToBottom() {
            var box = document.getElementById("faqBox");
            box.scrollTop = box.scrollHeight;
        }

        // Theme Toggle
        const body = document.body;
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
        }

        function toggleTheme() {
            body.classList.toggle('dark-mode');
            localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
        }
    </script>

</body>
</html>