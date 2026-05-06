<?php
require 'db_connect.php';

// redirect the user if they are not an admin
If ($_SESSION['level'] != 'admin')
{
  header('Location: index.php');
}
$stmt = $db->prepare("SELECT * FROM event_log ORDER BY date DESC");
$stmt->execute();
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>View Logs</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="Log Viewing" />
    <!-- <link rel="stylesheet" type="text/css" href="placeholder.css" /> -->
    
  </head>

  <body>
    <h1><a href="index.php">ALBVM</a></h1>
    
    <!-- no validation required as user must be admin to view this page -->
    <p>Hello <a href="profile.php?user=<?=$_SESSION['uname']?>"><?=nl2br(htmlentities($_SESSION['uname']))?></a>
    | <a onclick="return confirm(\'Are you sure you want to log out?\')" href="logout_processing.php">Logout</a></p>
    
    <h2>Event Logs</h2>
    
    <table align="left" width="60%" cellpadding="2" cellspacing="0" border="1">
    <tr><td><h3>Log Date</h3></td><td><h3>IP Address</h3></td><td><h3>Event</h3></td></tr>
    <?php
    foreach ($logs as $log)
    {
      echo '<tr><td>'.$log['date'].'</td><td>'.$log['ip_adr'].'</td><td><strong>'.$log['type'].'</strong> - '.$log['details'].'</td></tr>';
    }
    
    ?>
    </table>
  </body>
</html>