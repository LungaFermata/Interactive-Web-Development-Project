<?php
require 'db_connect.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Search Albums</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="Search albums" />
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
        echo '<p><a href="new_album.php">New Album</a></p>';
      }
    }
    else
    {
      echo '<form name="login_form" method="post" action="login_processing.php">
            <p><input type="text" name="uname" placeholder="Username" title="Username" />
            <input type="password" name="pword" placeholder="Password" title="Password" />
            <input type="submit" name="submit" value="login" class="middle" /></p>
            </form>
            <p>Or <a href="register.php">Register</a></p>';
    }
    ?>
    <form name="search_album" method="get" action="search.php" >
        <p><input type="text" name="search_term" placeholder="Enter search term..." title="Search" /> <input type="submit" name="submit" value="Search" /></p>
    </form>
    
    <?php
    // Execute a query if there's a search term in the URL data
    if (isset($_GET['search_term']))
    {
      echo '<h4>Search results for "'.nl2br(htmlentities($_GET['search_term'])).'"</h4>';
      
      // Put wildcard characters on each end of the search term
      $search_term = '%'.$_GET['search_term'].'%';
      
      
      $stmt = $db->prepare("SELECT * FROM album 
                            WHERE album_name LIKE ? OR artist LIKE ? OR record_label LIKE ?
                            ORDER BY artist ASC");

      // Provide the same value for both placeholders to search the title and content columns
      $stmt->execute( [$search_term, $search_term, $search_term] );      
      
      // Fetch all of the results as an array
      $result_data = $stmt->fetchAll();
           
      // Display results or a "no results" message as appropriate
      if (count($result_data) > 0)
      {          
        // as admin can delete all albums, checking for admin status before spliting into two seperate loops rather than evaluating every loop
        if ($_SESSION['level'] == 'admin')
        {
          // Loop through results to display links to albums and option to delete
          foreach($result_data as $row)
          {
            echo '<p><fieldset><legend><a href="album.php?id='.$row['album_id'].'">'.nl2br(htmlentities($row['album_name'])).' ('.$row['year'].')</a> | 
            <a onclick="return confirm(\'Are you sure you want to delete the album '.nl2br(htmlentities($row['album_name'])).'?\nThis will delete all associated data including tracks, comments and ratings.\')" href="album_deletion.php?id='.$row['album_id'].'">Delete Album</a>';
            echo '</legend><br/>By '.nl2br(htmlentities($row['artist'])).' | '.nl2br(htmlentities($row['record_label'])).'</p></fieldset>';
          }
        }
        else
        {
          // Loop through results to display links to album
          foreach($result_data as $row)
          {
            echo '<p><fieldset><legend><a href="album.php?id='.$row['album_id'].'">'.nl2br(htmlentities($row['album_name'])).' ('.$row['year'].') </a>';
            echo '</legend><br/>By '.$row['artist'].' | '.$row['record_label'].'</p></fieldset>';
          }
        }
      }
      else
      {
        echo '<p>No results found.</p>';
      }
    }
    ?>
  </body>
</html>