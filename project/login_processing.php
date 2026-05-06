<?php
require 'db_connect.php';

// If the request includes form data...
if (isset($_POST['submit']))
{ // Validate username and password match

  $stmt = $db->prepare("SELECT username, access_level, password FROM user WHERE username=? ");
  $stmt->execute( [$_POST['uname']] );
  $user = $stmt->fetch();
  
  if (($user) && (password_verify($_POST['pword'], $user['password'])))
  { // if the username matched a database entry and the password matches the db hash, create session data to hold their username and access level
    $e_type = 'Login';
    $e_detail = $_POST['uname'].' logged in';
    $log = $db->prepare("INSERT INTO event_log (type, ip_adr, details) VALUES (?, ?, ?)");
    $log->execute( [ $e_type, $_SERVER['REMOTE_ADDR'], $e_detail ]);
    $_SESSION['uname'] = $user['username'];
    $_SESSION['level'] = $user['access_level'];
    header('Location: index.php');
  }
  else
  { // Display an error message and link back to the previous page
    $e_type = 'Login Attempt';
    $e_detail = 'Failed login attempt with username of '.$_POST['uname'];
    $log = $db->prepare("INSERT INTO event_log (type, ip_adr, details) VALUES (?, ?, ?)");
    $log->execute( [ $e_type, $_SERVER['REMOTE_ADDR'], $e_detail ]);
    echo 'Login failed!';
    echo '<p><a href="javascript: window.history.back()">Go Back</a>';
  }
}
else
{ // Show message if the form has not been submitted
  echo '<p>Please login from <a href="index.php">the Index</a>.</p>';
}
?>