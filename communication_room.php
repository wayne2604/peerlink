<?php
session_start();
include 'includes/db_connect.php';

// 1. Security: Check Login
if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header("Location: index.php");
    exit();
}

$booking_id = intval($_GET['booking_id']);
$user_id = intval($_SESSION['user_id']);
$user_role = $_SESSION['role']; 

// 2. Handle "Claim Chat" Action
if (isset($_POST['claim_chat'])) {
    if ($user_role == 'student') {
        $conn->query("UPDATE bookings SET student_id = $user_id WHERE id = $booking_id");
    } elseif ($user_role == 'listener') {
        $conn->query("UPDATE bookings SET listener_id = $user_id WHERE id = $booking_id");
    }
    header("Location: communication_room.php?booking_id=$booking_id"); 
    exit();
}

// 3. Fetch Booking Details
$sql = "SELECT * FROM bookings WHERE id = $booking_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Error: Chat not found (Invalid Booking ID).");
}

$booking = $result->fetch_assoc();

// 4. Permission Check
$is_authorized = false;
$mismatch_msg = "";

if ($user_role == 'student') {
    if (intval($booking['student_id']) === $user_id) $is_authorized = true;
    else $mismatch_msg = "Chat belongs to Student ID: " . $booking['student_id'];
} elseif ($user_role == 'listener') {
    if (intval($booking['listener_id']) === $user_id) $is_authorized = true;
    else $mismatch_msg = "Chat belongs to Listener ID: " . $booking['listener_id'];
}

// 5. Unauthorized View
if (!$is_authorized) {
    echo "<h2 style='text-align:center; margin-top:50px;'>⛔ Permission Error</h2>";
    echo "<p style='text-align:center;'>$mismatch_msg</p>";
    echo "<form method='POST' style='text-align:center;'><button name='claim_chat' style='padding:10px;'>Fix: Claim This Chat</button></form>";
    exit();
}

// 6. Determine Chat Title
$chat_title = "Chat";
if ($user_role == 'listener') {
    $chat_title = "Client: " . htmlspecialchars($booking['form_name']);
} else {
    $l_id = $booking['listener_id'];
    $l_res = $conn->query("SELECT alias FROM listener_profiles WHERE user_id = $l_id");
    if($l_res->num_rows > 0){
        $l_data = $l_res->fetch_assoc();
        $chat_title = "Peer Listener: " . htmlspecialchars($l_data['alias']);
    } else {
        $chat_title = "Peer Listener";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Chat - PeerLink</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <style>
        /* DESKTOP STYLES (Default) */
        body { background-color: #f4f7f6; margin: 0; }
        
        .chat-container { 
            max-width: 800px; 
            margin: 30px auto; 
            background: white; 
            border-radius: 20px; 
            height: 85vh; 
            display: flex; 
            flex-direction: column; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            overflow: hidden; 
            border: 1px solid #ddd;
        }

        .chat-header { 
            background: #003366; 
            color: white; 
            padding: 15px 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-shrink: 0; 
            z-index: 10;
        }

        .chat-body { 
            flex: 1; 
            padding: 20px; 
            overflow-y: auto; 
            background: #f4f6f9; 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
            scroll-behavior: smooth; 
        }

        .chat-footer { 
            padding: 15px; 
            border-top: 1px solid #ddd; 
            background: white; 
            display: flex; 
            gap: 10px; 
            flex-shrink: 0; 
            align-items: center;
        }

        /* Message Bubbles */
        .message-row { display: flex; width: 100%; margin-bottom: 5px; }
        .my-message { justify-content: flex-end; }
        .their-message { justify-content: flex-start; }
        .chat-bubble { padding: 10px 16px; border-radius: 18px; font-size: 15px; max-width: 75%; word-wrap: break-word; line-height: 1.4; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .bubble-blue { background-color: #007bff; color: white; border-bottom-right-radius: 4px; }
        .bubble-gray { background-color: #e4e6eb; color: #050505; border-bottom-left-radius: 4px; }

        .btn-end { background-color: #444; color: white; padding: 6px 12px; border: none; border-radius: 15px; cursor: pointer; font-weight: bold; text-decoration: none; font-size: 12px; margin-left: 5px; }
        .btn-report { background-color: #dc3545; }

        /* =========================================
           SMARTPHONE VIEW FIXES (Responsive) 
           ========================================= */
        @media (max-width: 768px) {
            body, html {
                height: 100%;
                overflow: hidden; /* Prevent body scroll */
                background-color: white;
            }

            .chat-container {
                width: 100%;
                max-width: 100%;
                height: 100vh; /* Full Height */
                margin: 0;
                border-radius: 0;
                border: none;
                box-shadow: none;
            }

            .chat-header {
                padding: 12px 15px;
                font-size: 16px;
            }

            .chat-body {
                padding: 15px 10px;
            }

            .chat-footer {
                padding: 10px;
                background: #fff;
                border-top: 1px solid #eee;
                position: sticky;
                bottom: 0;
            }

            /* Adjust Input for Mobile */
            #msg_input {
                font-size: 16px; /* Prevents iOS zoom */
                padding: 10px 15px;
            }

            .btn-end {
                padding: 8px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body class="dashboard-page">

    <div class="chat-container">
        <div class="chat-header">
            <h3 style="margin:0; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 50%;">
                <?php echo $chat_title; ?>
            </h3>
            
            <div style="display: flex; align-items: center;">
                <?php if ($user_role == 'listener'): ?>
                    <form action="terminate_session.php" method="POST" style="margin:0;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                        <button type="submit" name="action" value="end" class="btn-end">End</button>
                    </form>
                    <form action="terminate_session.php" method="POST" style="margin:0;" onsubmit="return confirm('Report this student?');">
                        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                        <button type="submit" name="action" value="report" class="btn-end btn-report">Report</button>
                    </form>
                <?php endif; ?>

                <?php $back_link = ($user_role == 'listener') ? 'listener_dashboard.php' : 'student_home.php'; ?>
                <a href="<?php echo $back_link; ?>" class="btn-end" style="background:#222;">Exit</a>
            </div>
        </div>

        <div class="chat-body" id="chat_box"></div>

        <div class="chat-footer">
            <input type="text" id="msg_input" style="flex:1; padding:12px 20px; border-radius:25px; border:1px solid #ccc; outline:none;" placeholder="Type a message...">
            <button onclick="sendMessage()" style="padding:10px 20px; border-radius:25px; border:none; background:#003366; color:white; font-weight:bold; cursor:pointer; margin-left: 5px;">
                Send
            </button>
        </div>
    </div>

    <script>
        var booking_id = <?php echo $booking_id; ?>;
        
        function loadMessages() {
            $.ajax({
                url: "load_data.php", 
                method: "GET",
                data: { booking_id: booking_id },
                success: function(data) {
                    var chatBox = $("#chat_box");
                    var currentScroll = chatBox.scrollTop();
                    var scrollHeight = chatBox[0].scrollHeight;
                    var height = chatBox.height();
                    var wasAtBottom = (scrollHeight - currentScroll <= height + 100); // 100px threshold

                    $("#chat_box").html(data);

                    if (wasAtBottom) { scrollToBottom(); }
                }
            });
        }

        function sendMessage() {
            var msg = $("#msg_input").val();
            if (msg.trim() == "") return;

            $.ajax({
                url: "post_data.php",
                method: "POST",
                data: { booking_id: booking_id, message: msg },
                success: function(response) {
                    if (response.trim() === "Sent") {
                        $("#msg_input").val(""); 
                        loadMessages(); 
                        setTimeout(scrollToBottom, 100); 
                    } else {
                        alert("Message Failed: " + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Connection Error: " + error);
                }
            });
        }

        function scrollToBottom() {
            var chatBox = document.getElementById("chat_box");
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        $(document).ready(function() {
            loadMessages();
            setInterval(loadMessages, 5000); 
            setTimeout(scrollToBottom, 500);
        });

        $("#msg_input").keypress(function(e) {
            if(e.which == 13) sendMessage();
        });
    </script>
</body>
</html>