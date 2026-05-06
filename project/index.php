<?php
require 'db_connect.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ALBVM</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="ALBVM Index" />
    <!-- <link rel="stylesheet" type="text/css" href="placeholder.css" /> -->
  </head>

  <body>
    <h1><a href="index.php">ALBVM</a></h1>
    <?php
    if ($_SESSION['uname'])
    {
      echo '<p>Hello <a href="profile.php?user='.$_SESSION['uname'].'">'.nl2br(htmlentities($_SESSION['uname'])).'</a>
             | <a href="edit_profile.php">Edit Profile</a>
             | <a onclick="return confirm(\'Are you sure you want to log out?\')" href="logout_processing.php">Logout</a></p>';
      
      if ($_SESSION['level'] == 'admin')
      {
        echo '<p><a href="new_album.php">New Album</a> | <a href="view_log.php">View Logs</a></p>';
      }
    }
    else
    {
      echo '<form name="login_form" method="post" action="login_processing.php">
            <p><input type="text" name="uname" placeholder="Username" title="Username" required />
            <input type="password" name="pword" placeholder="Password" title="Password" required />
            <input type="submit" name="submit" value="login" class="middle" /></p>
            </form>
            <p>Or <a href="register.php">Register</a></p>';
    }
    ?>
    <form name="search_threads" method="get" action="search.php" >
        <p><input type="text" name="search_term" placeholder="Enter search term..." title="Search" /> <input type="submit" name="submit" value="Search" /></p>
    </form>
    
    <h3>All Albums</h3>
    <p>Sort by <a href="index.php?sort=year">Year</a> | <a href="index.php?sort=artist">Artist</a> | <a href="index.php?sort=album_name">Title</a></p>
    
    <?php
    
    // Array of non-default columns, best displayed in ascending order so simply placed into statement if matching
    $acolumns = ['artist', 'album_name'];
    if (isset($_GET['sort']) && in_array($_GET['sort'], $acolumns))
    {
     $column = $_GET['sort'];
    }
    // Defaults to sorting by year decending, as such $_GET['sort'] == year would be the same as invalid values and isn't processed seperately
    else
    {
      $column = 'year DESC';
    }
    
    $stmt = $db->prepare("SELECT * FROM album 
                          ORDER BY $column");
    $stmt->execute();
    
    // Fetch all of the results as an array
    $result_data = $stmt->fetchAll();

    // as admin can delete all albums, checking for admin status before spliting into two seperate loops rather than evaluating every loop
    if ($_SESSION['level'] == 'admin')
    {
      // Loop through results to display links to threads and option to delete
      foreach($result_data as $row)
      {
        echo '<p><fieldset><legend><a href="album.php?id='.$row['album_id'].'">'.nl2br(htmlentities($row['album_name'])).' ('.$row['year'].')</a> | 
        <a onclick="return confirm(\'Are you sure you want to delete the album '.nl2br(htmlentities($row['album_name'])).'?\nThis will delete all associated data including tracks, comments and ratings.\')" href="album_deletion.php?id='.$row['album_id'].'">Delete Album</a>';
        echo '</legend><br/>By '.nl2br(htmlentities($row['artist'])).' | '.nl2br(htmlentities($row['record_label'])).'</p></fieldset>';
      }
    }
    else
    {
      // Loop through results to display links to threads
      foreach($result_data as $row)
      {
        echo '<p><fieldset><legend><a href="album.php?id='.$row['album_id'].'">'.nl2br(htmlentities($row['album_name'])).' ('.$row['year'].') </a>';
        echo '</legend><br/>By '.nl2br(htmlentities($row['artist'])).' | '.nl2br(htmlentities($row['record_label'])).'</p></fieldset>';
      }
    }
    ?>
  </body>
</html>
