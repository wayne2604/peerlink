<?php
date_default_timezone_set('Asia/Manila'); // Or your correct timezone
$servername = "bhvkf1aoswgiodogcvyx-mysql.services.clever-cloud.com"; // Your Online Hostname
$username = "u3m26r6ajgfgtuka";             // Your Online Username
$password = "dVLj6BgUDOeMJTadX6Cr";        // Your Online Password
$dbname = "bhvkf1aoswgiodogcvyx";      // Your Online Database Name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
?>