<?php
date_default_timezone_set('Asia/Manila'); // Or your correct timezone
$servername = "sql308.infinityfree.com"; // Your Online Hostname
$username = "if0_41094727";             // Your Online Username
$password = "ZoxuESaPGM7X083";        // Your Online Password
$dbname = "if0_41094727_peerlink";      // Your Online Database Name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
?>