<?php
require 'db_connect.php';

// log before session destroy to capture the username
$e_type = 'Logout';
$e_detail = $_SESSION['uname'].' logged out';
$log = $db->prepare("INSERT INTO event_log (type, ip_adr, details) VALUES (?, ?, ?)");
$log->execute( [ $e_type, $_SERVER['REMOTE_ADDR'], $e_detail ]);

session_destroy();
// making the cookie expire just to be safe
setcookie(session_name(), '', 1);
header('Location: index.php');
?>