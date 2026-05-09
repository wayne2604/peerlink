<?php
session_start();
include 'includes/db_connect.php';

// 1. Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'listener') {
    header("Location: index.php");
    exit();
}

$listener_id = $_SESSION['user_id'];

// 2. Handle Profile Update
if (isset($_POST['update_profile'])) {
    $alias = $conn->real_escape_string($_POST['alias']);
    $specialty = $conn->real_escape_string($_POST['specialty']);
    $personality = $conn->real_escape_string($_POST['personality']);
    $approach = $conn->real_escape_string($_POST['approach']);
    $religion = $conn->real_escape_string($_POST['religion']);
    $belief = $conn->real_escape_string($_POST['belief']);
    $focus = $conn->real_escape_string($_POST['focus']);
    $goal = $conn->real_escape_string($_POST['goal']);
    $reminder = $conn->real_escape_string($_POST['reminder']);
    $hobbies = $conn->real_escape_string($_POST['hobbies']);

    $image_sql_part = "";
    if (isset($_FILES['avatar_upload']) && $_FILES['avatar_upload']['error'] == 0) {
        $target_dir = "images/";
        $file_extension = pathinfo($_FILES["avatar_upload"]["name"], PATHINFO_EXTENSION);
        $new_filename = "avatar_" . $listener_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        if (move_uploaded_file($_FILES["avatar_upload"]["tmp_name"], $target_file)) {
            $image_sql_part = ", avatar='$new_filename'";
        }
    }

    $sql = "UPDATE listener_profiles SET 
            alias='$alias', specialty='$specialty', personality='$personality', 
            approach='$approach', religion='$religion', belief='$belief', 
            focus='$focus', goal='$goal', reminder='$reminder', hobbies='$hobbies' 
            $image_sql_part 
            WHERE user_id='$listener_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='listener_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// 3. Fetch Data
$active_chat = $conn->query("SELECT * FROM bookings WHERE listener_id = $listener_id AND status = 'accepted' LIMIT 1")->fetch_assoc();
$profile = $conn->query("SELECT * FROM listener_profiles WHERE user_id = $listener_id")->fetch_assoc();
$pending_requests = $conn->query("SELECT * FROM bookings WHERE listener_id = $listener_id AND status = 'pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigator Dashboard</title>
    <link rel="icon" type="image/png" href="images/peerlink_navigator.png">
    <link rel="stylesheet" href="css/style.css?v=101">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Specific Styles for Form Inputs in Profile */
        .profile-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full-width { grid-column: span 2; }
        .profile-input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; }
        .avatar-preview-container { grid-column: span 2; text-align: center; margin-bottom: 20px; }
        .current-avatar { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #003366; margin-bottom: 10px; }
        
        @media (max-width: 768px) {
            .profile-form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
</head>
<body class="dashboard-page">

    <div class="top-bar">
        <div style="display:flex; align-items:center;">
            <div class="mobile-toggle" onclick="toggleSidebar()">☰</div>
            <div class="top-title">PeerLink Navigator</div>
        </div>
        <div class="settings-container">
            <div class="settings-icon" onclick="toggleSettings()">⚙️</div>
            <div id="settingsDropdown" class="dropdown-menu">
                <a href="#" onclick="toggleTheme(); return false;">🌙 Theme</a>
                <a href="logout.php" style="color: #dc3545;">🚪 Logout</a>
            </div>
        </div>
    </div>

    <div class="dashboard-wrapper">
        
        <div class="sidebar" id="mySidebar">
            <div class="sidebar-header">Navigator Tools</div>
            
            <button class="nav-btn active" onclick="openTab('requests', this); toggleSidebar()">
                <span>📩</span> Requests
            </button>
            <button class="nav-btn" onclick="openTab('profile', this); toggleSidebar()">
                <span>👤</span> My Profile
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

            <div id="requests" class="tab-content active">
                <div class="page-title">Pending Requests</div>
                
                <?php if ($active_chat): ?>
                    <div style="background: white; border-left: 6px solid #2196f3; padding: 25px; margin-bottom: 30px; border-radius: 12px; text-align: center; box-shadow: var(--shadow);">
                        <h2 style="margin: 0; color: #003366; font-size: 20px;">⚠️ Ongoing Session</h2>
                        <p style="margin: 10px 0; color: #555;">You are chatting with <strong><?php echo htmlspecialchars($active_chat['form_name']); ?></strong>.</p>
                        <a href="communication_room.php?booking_id=<?php echo $active_chat['id']; ?>" class="btn-action btn-primary" style="background-color: #2196f3;">RETURN TO CHAT</a>
                    </div>
                <?php else: ?>
                    <div class="card-grid">
                    <?php if ($pending_requests->num_rows > 0) {
                        $count = 1;
                        while($row = $pending_requests->fetch_assoc()) { ?>
                        <div class="request-card">
                            <div style="text-align: right; color: #6fa3ef; font-weight: bold; margin-bottom: 10px;">Client <?php echo $count; ?></div>
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Name</label>
                                <div style="padding: 10px; background: #f9f9f9; border-radius: 8px; border: 1px solid #eee;"><?php echo $row['form_name'] ? $row['form_name'] : "Anonymous"; ?></div>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Topic</label>
                                <div style="padding: 10px; background: #f9f9f9; border-radius: 8px; border: 1px solid #eee;"><?php echo $row['form_topic']; ?></div>
                            </div>
                            <div style="display:flex; gap:10px;">
                                <form action="process_request.php" method="POST" style="flex:1;">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="accept" class="btn-action btn-primary" style="width:100%;">ACCEPT</button>
                                </form>
                                <form action="process_request.php" method="POST" style="flex:1;">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="reject" class="btn-action" style="width:100%; background-color:#c62828; color:white;">REJECT</button>
                                </form>
                            </div>
                        </div>
                    <?php $count++; } } else { echo "<p>No pending requests.</p>"; } ?>
                    </div>
                <?php endif; ?>
            </div>

            <div id="profile" class="tab-content">
                <div class="page-title">Edit My Profile</div>
                <div class="form-card" style="max-width: 800px; margin: 0 auto;">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="profile-form-grid">
                            <div class="avatar-preview-container">
                                <?php $avatar = !empty($profile['avatar']) ? $profile['avatar'] : 'default_avatar.png'; ?>
                                <img src="images/<?php echo $avatar; ?>" class="current-avatar" alt="Current Avatar"><br>
                                <input type="file" name="avatar_upload" accept="image/*">
                            </div>
                            <div><label>Alias</label><input type="text" name="alias" class="profile-input" value="<?php echo isset($profile['alias']) ? $profile['alias'] : ''; ?>" required></div>
                            <div><label>Specialty</label><input type="text" name="specialty" class="profile-input" value="<?php echo isset($profile['specialty']) ? $profile['specialty'] : ''; ?>"></div>
                            <div><label>Personality</label><input type="text" name="personality" class="profile-input" value="<?php echo isset($profile['personality']) ? $profile['personality'] : ''; ?>"></div>
                            <div><label>Approach</label><input type="text" name="approach" class="profile-input" value="<?php echo isset($profile['approach']) ? $profile['approach'] : ''; ?>"></div>
                            <div><label>Religion</label><input type="text" name="religion" class="profile-input" value="<?php echo isset($profile['religion']) ? $profile['religion'] : ''; ?>"></div>
                            <div><label>Belief</label><input type="text" name="belief" class="profile-input" value="<?php echo isset($profile['belief']) ? $profile['belief'] : ''; ?>"></div>
                            <div class="full-width"><label>Focus</label><input type="text" name="focus" class="profile-input" value="<?php echo isset($profile['focus']) ? $profile['focus'] : ''; ?>"></div>
                            <div class="full-width"><label>Goal</label><input type="text" name="goal" class="profile-input" value="<?php echo isset($profile['goal']) ? $profile['goal'] : ''; ?>"></div>
                            <div class="full-width"><label>Reminder</label><input type="text" name="reminder" class="profile-input" value="<?php echo isset($profile['reminder']) ? $profile['reminder'] : ''; ?>"></div>
                            <div class="full-width"><label>Hobbies</label><textarea name="hobbies" class="profile-input" style="height: 80px;"><?php echo isset($profile['hobbies']) ? $profile['hobbies'] : ''; ?></textarea></div>
                        </div>
                        <div style="text-align: center; margin-top: 20px;">
                            <button type="submit" name="update_profile" class="btn-action btn-primary">SAVE PROFILE</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="ask" class="tab-content">
                <div class="page-title">Ask Us Anything</div>
                <div class="faq-container">
                    <div class="faq-header">PeerLink Automated Support 🤖</div>
                    <div class="faq-body" id="faqBox">
                        <div class="message-row their-message"><div class="chat-bubble bubble-gray">Hello! I'm the PeerLink Bot. Click a question below and I'll help you out! 👇</div></div>
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

    <script>
        function openTab(tabName, btnElement) {
            $(".tab-content").removeClass("active"); $(".nav-btn").removeClass("active");
            $("#" + tabName).addClass("active"); $(btnElement).addClass("active");
        }
        function toggleSidebar() { $("#mySidebar").toggleClass("active"); }
        function toggleSettings() { $("#settingsDropdown").toggleClass("show-menu"); }
        
        function askBot(topic) {
            var q = "", a = "";
            if (topic === 'what') { q = "What is PeerLink?"; a = "PeerLink is a safe space for students to connect with trained Peer Listeners."; } 
            else if (topic === 'how') { q = "How do I book a listener?"; a = "Go to 'Listener Booking' tab, browse profiles, and click 'Select Peer'."; }
            else if (topic === 'privacy') { q = "Is this anonymous?"; a = "Yes, unless there is a risk of harm."; }
            else if (topic === 'urgent') { q = "I need urgent help."; a = "⚠️ Please contact the Guidance Counselor immediately."; }
            $("#faqBox").append('<div class="message-row my-message"><div class="chat-bubble bubble-blue">' + q + '</div></div>');
            scrollToBottom();
            setTimeout(function() { $("#faqBox").append('<div class="message-row their-message"><div class="chat-bubble bubble-gray">' + a + '</div></div>'); scrollToBottom(); }, 500);
        }
        function scrollToBottom() { var box = document.getElementById("faqBox"); box.scrollTop = box.scrollHeight; }
        
        const body = document.body; if (localStorage.getItem('theme') === 'dark') body.classList.add('dark-mode');
        function toggleTheme() { body.classList.toggle('dark-mode'); localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light'); }
    </script>
</body>
</html>