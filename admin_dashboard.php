<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'peerlink7@gmail.com') {
        header("Location: index.php");
        exit();
    }
}

if (isset($_POST['action'])) {
    $user_id = intval($_POST['user_id']);
    $action = $_POST['action'];
    if ($action === 'approve') {
        $conn->query("UPDATE users SET is_verified = 1 WHERE id = $user_id");
        echo "<script>alert('Listener Approved!');</script>";
    } elseif ($action === 'reject') {
        $conn->query("DELETE FROM users WHERE id = $user_id");
        echo "<script>alert('Listener Rejected.');</script>";
    }
}

$res_pending = $conn->query("SELECT * FROM users WHERE role = 'listener' AND is_verified = 0");
$res_active = $conn->query("SELECT * FROM users WHERE role = 'listener' AND is_verified = 1");
$res_students = $conn->query("SELECT * FROM users WHERE role = 'student'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="images/peerlink_navigator.png">
    <link rel="stylesheet" href="css/style.css?v=101">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="dashboard-page">

    <div class="top-bar">
        <div style="display:flex; align-items:center;">
            <div class="mobile-toggle" onclick="toggleSidebar()">☰</div>
            <div class="top-title">PeerLink Admin</div>
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
            <div class="sidebar-header">Administration</div>
            <button class="nav-btn active" onclick="openTab('pending', this); toggleSidebar()"><span>⏳</span> Pending</button>
            <button class="nav-btn" onclick="openTab('active', this); toggleSidebar()"><span>✅</span> Verified Listeners</button>
            <button class="nav-btn" onclick="openTab('students', this); toggleSidebar()"><span>🎓</span> Student List</button>
            <button class="nav-btn" onclick="openTab('ask', this); toggleSidebar()"><span>🤖</span> Ask Us</button>
            <button class="nav-btn" onclick="openTab('socials', this); toggleSidebar()"><span>🌍</span> Socials</button>
            <a href="logout.php" class="logout-btn"><span>🚪</span> LOGOUT</a>
        </div>

        <div class="main-content">
            
            <div id="pending" class="tab-content active">
                <div class="page-title">Pending Approvals</div>
                <div class="admin-card">
                    <?php if ($res_pending->num_rows > 0): ?>
                    <table class="admin-table">
                        <thead><tr><th>Name</th><th>Email</th><th>Action</th></tr></thead>
                        <tbody><?php while($row = $res_pending->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['real_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn-action btn-primary" style="background:#28a745;">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn-action btn-primary" style="background:#dc3545;">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?></tbody>
                    </table>
                    <?php else: ?><p style="text-align:center; padding:20px;">No pending listener applications.</p><?php endif; ?>
                </div>
            </div>

            <div id="active" class="tab-content">
                <div class="page-title">Verified Listeners</div>
                <div class="admin-card">
                    <table class="admin-table">
                        <thead><tr><th>Name</th><th>Email</th><th>Status</th></tr></thead>
                        <tbody><?php if ($res_active->num_rows > 0): while($row = $res_active->fetch_assoc()): ?>
                            <tr><td><?php echo htmlspecialchars($row['real_name']); ?></td><td><?php echo htmlspecialchars($row['email']); ?></td><td><span style="color:#28a745; font-weight:bold;">Verified</span></td></tr>
                        <?php endwhile; else: ?><tr><td colspan="3" style="text-align:center;">No active listeners.</td></tr><?php endif; ?></tbody>
                    </table>
                </div>
            </div>

            <div id="students" class="tab-content">
                <div class="page-title">Registered Students</div>
                <div class="admin-card">
                    <table class="admin-table">
                        <thead><tr><th>Name</th><th>Grade/Section</th><th>Email</th></tr></thead>
                        <tbody><?php if ($res_students->num_rows > 0): while($row = $res_students->fetch_assoc()): ?>
                            <tr><td><?php echo htmlspecialchars($row['real_name']); ?></td><td><?php echo htmlspecialchars($row['grade_section']); ?></td><td><?php echo htmlspecialchars($row['email']); ?></td></tr>
                        <?php endwhile; else: ?><tr><td colspan="3" style="text-align:center;">No students found.</td></tr><?php endif; ?></tbody>
                    </table>
                </div>
            </div>

            <div id="ask" class="tab-content">
                <div class="page-title">Ask Us Anything</div>
                <div class="faq-container">
                    <div class="faq-header">PeerLink Automated Support 🤖</div>
                    <div class="faq-body" id="faqBox"><div class="message-row their-message"><div class="chat-bubble bubble-gray">Hello Admin!</div></div></div>
                    <div class="faq-footer">
                        <div style="display:flex; justify-content:center; gap:10px;"><button class="btn-action btn-primary" onclick="askBot('what')">What is PeerLink?</button></div>
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
        function openTab(id, btn) { $(".tab-content").removeClass("active"); $(".nav-btn").removeClass("active"); $("#" + id).addClass("active"); $(btn).addClass("active"); }
        function toggleSidebar() { $("#mySidebar").toggleClass("active"); }
        function toggleSettings() { $("#settingsDropdown").toggleClass("show-menu"); }
        function askBot(t) { var a=""; if(t=='what') a="PeerLink is a safe peer support space."; $("#faqBox").append('<div class="message-row my-message"><div class="chat-bubble bubble-blue">...</div></div>'); setTimeout(()=>$("#faqBox").append('<div class="message-row their-message"><div class="chat-bubble bubble-gray">'+a+'</div></div>'), 500); }
        const b = document.body; if(localStorage.getItem('theme')=='dark') b.classList.add('dark-mode'); function toggleTheme(){ b.classList.toggle('dark-mode'); localStorage.setItem('theme', b.classList.contains('dark-mode') ? 'dark' : 'light'); }
    </script>
</body>
</html>