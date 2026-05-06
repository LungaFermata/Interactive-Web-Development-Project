<?php
  require 'db_connect.php';

// If user is not admin send them to index
if ($_SESSION['level'] != 'admin')
{
  header('Location: index.php');
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id']))
{ // If there is no "id" URL data or it isn't a number
  header('location: index.php');
}
$stmt = $db->prepare("SELECT album_name, year FROM album WHERE album_id = ?;");
$stmt->execute( [$_GET['id']] );
$album = $stmt->fetch();

$stmt = $db->prepare("DELETE FROM album WHERE album_id=?");
$result = $stmt->execute( [$_GET['id']]);

if ($result)
{ // if the atempt was successful inform the user

  $e_type = 'Album Deleted';
  $e_detail = $album['album_name'].' ('.$album['year'].') deleted by '.$_SESSION['uname'];
  $log = $db->prepare("INSERT INTO event_log (type, ip_adr, details) VALUES (?, ?, ?)");
  $log->execute( [ $e_type, $_SERVER['REMOTE_ADDR'], $e_detail ]);

  echo "<p>Album deleted!<br></p>";
  echo '<p><a href="index.php">Return to index</a></p>';
}
else
{ // otherwise provide generic error message
  //echo $stmt->errorInfo()[2];
  echo '<p>Something went wrong</p>';
  echo '<p><a href="javascript: window.history.back()">Return to form</a></p>';
}
?>