<?php
require 'db_connect.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id']))
{ // If there is no "id" URL data or it isn't a number
  echo '<h3>Invalid or Missing Album ID</h3>';
  echo '<p><a href="javascript: window.history.back()">Go Back</a>';
  echo '<p><a href="index.php">Go to Index</a>.';
  exit;  
}

//$_SESSION['uname'] is updated in in db_connect.php to contain false if null
if ($_SESSION['uname'])
{
  //Attempts to join user's rating to this query to minimise requests to server
  $stmt = $db->prepare("SELECT a.*, r.score AS score FROM album_view AS a LEFT JOIN rating AS r ON a.album_id = r.album_id AND r.username = ? WHERE a.album_id = ?;");
  $stmt->execute( [$_SESSION['uname'], $_GET['id']] );
  $album = $stmt->fetch();
}
else
{ // using a view which has already calculated the average rating
  $stmt = $db->prepare("SELECT * FROM album_view WHERE album_id = ?;");
  $stmt->execute( [$_GET['id']] );
  $album = $stmt->fetch();
}

if (!$album)
{ // If no data (no album with that ID in the database)
  echo '<h3>Album Not Found</h3>';
  echo '<p><a href="javascript: window.history.back()">Go Back</a>';
  echo '<p><a href="index.php">Go to Index</a>.';
  exit;  
}

// fetching track info from the database here for use in calculating the duration  
$stmt = $db->prepare("SELECT track_name, track_id, duration FROM track WHERE album_id = ? ORDER BY track_id ASC");
$stmt->execute( [$_GET['id']] );
$track = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
  <head>
    <title><?= nl2br(htmlentities($album['album_name'])) ?></title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="View Albums" />
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
    <form name="search_threads" method="get" action="search.php" >
        <p><input type="text" name="search_term" placeholder="Enter search term..." title="Search" /> <input type="submit" name="submit" value="Search" /></p>
    </form>
    
		<?php
    // Display the album's details
    echo '<fieldset><legend><h2>'.nl2br(htmlentities($album['album_name'])).'</h2></legend>';
    echo '<strong>Artist: </strong> '.nl2br(htmlentities($album['artist'])).'
          <p><strong>Released: </strong> '.nl2br(htmlentities($album['year'])).'</p>
          <p><strong>Record Label: </strong> '.nl2br(htmlentities($album['record_label'])).'</p>';
          
    // if there are tracks, calculate the duration
    if (count($track) > 0)
    {// only creating the minute variable if the album has a duration
      //using output buffer to loop through tracks and calculate a running total for duration at the same time while displaying tracks later
      ob_start();
      echo '<table align="left" width="40%" cellpadding="0" cellspacing="0" border="0">
      <tr><td><strong>Track Number</strong></td><td><strong>Track Name</strong></td><td><strong>Duration</strong></td><td></td></tr>';
      // Loop through results to display comments
      $tnum = 1;
      $tduration = 0;
      foreach($track as $row)
      {
        // collect the running total for the album duration
        $tduration += $row['duration'];
        $rmin = intdiv($row['duration'],60);
        // added the nl2br(htmlentities()) just because the string is long and technically user generated, even if only by admins
        echo '<tr><td>'.$tnum.')</td><td>'.nl2br(htmlentities($row['track_name'])).'</td>'; 
        if ($rmin >= 60)
        {
          echo '<td>'.intdiv($rmin,60).':'.($rmin % 60).':'.sprintf("%02d",($row['duration'] % 60)).'</td>';
        }
        else
        {
          echo '<td>'.$rmin.':'.sprintf("%02d",($row['duration'] % 60)).'</td>';
        }
        echo '<td><form name="fav_track" method="post" action="fav_track.php" >
              <input type="hidden" name="album_id" value="'.$album['album_id'].'">
              <input type="hidden" name="track_id" value="'.$row['track_id'].'">
              <input type="submit" name="submit" value="Favourite" /></form><br></td>';
        $tnum++;
      }
      echo '</table>';
      // saving the output buffer to a variable which is echo'd later on
      $track_html = ob_get_clean();
      
      $minute = intdiv($tduration,60);
      if ($minute >= 60)
      {// using sprintf to format the numbers properly
        echo '<p><strong>Duration: </strong> '.intdiv($minute,60).':'.sprintf("%02d",($minute % 60)).':'.sprintf("%02d",($tduration % 60));
      }
      else
      {
        echo '<p><strong>Duration: </strong> '.$minute.':'.sprintf("%02d",($tduration % 60));
      }
    }
    else
    {
      echo '<p><strong>Duration: </strong> N/A';
    }
    if (!$album['average_score'])
    {
      echo '<p><strong>Rating: </strong> No Rating';
    }
    else
    {
      echo '<p><strong>Rating: </strong> '.round($album['average_score'],2).'/5';
    }
    
    if ($_SESSION['uname'])
    {
      if (isset($album['score']))
      {
        echo '<form name="rate_album" method="post" action="rate.php" >';
        for ($x = 1; $x <= 5; $x++)
        {
          $text = $x == $album['score'] ? 'checked="checked"' : '';
          echo '<label><input type="radio" name="score" value="'.$x.'"'.$text.' /> '.$x.'</label>';
        }
        echo '<input type="hidden" name="album_id" value="'.$album['album_id'].'">
              <input type="hidden" name="edit" value="true">
              <input type="submit" name="submit" value="Change" />
              </form></p></fieldset>';
      }
      else
      {
        echo '<form name="rate_album" method="post" action="rate.php" >
              <label><input type="radio" name="score" value="1" /> 1</label>
              <label><input type="radio" name="score" value="2" /> 2</label>
              <label><input type="radio" name="score" value="3" /> 3</label>
              <label><input type="radio" name="score" value="4" /> 4</label>
              <label><input type="radio" name="score" value="5" /> 5</label>
              <input type="hidden" name="album_id" value="'.$album['album_id'].'">
              <input type="submit" name="submit" value="Rate" />
              </form></p></fieldset>';
      }
      echo '<form name="fav_album" method="post" action="fav_album.php" >
            <input type="hidden" name="album_id" value="'.$album['album_id'].'">
            <input type="submit" name="submit" value="Favourite Album" /></form>';
    }
    ?>
            


    <fieldset><legend><h3>Track List</h3></legend>
    <?php
    // Display results or a "no comments" message as appropriate
    if (count($track) > 0)
    {      
      echo $track_html;
    }
    else
    {
      echo 'No tracks listed.';
    }
    echo '</fieldset>';
    
    // if user is admin allow them to edit or delete the album's information, edit currently not in use
    if ($_SESSION['level'] == 'admin')
    {
    echo '<a onclick="return confirm(\'Are you sure you want to delete the album '.$album['album_name'].'?\nThis will delete all associated data including tracks, comments and ratings.\')" href="album_deletion.php?id='.$album['album_id'].'">Delete Album</a>';
      //echo ' | <a href="edit_album.php?id='.$_GET['id'].'">Edit Album</a>';
    }
    ?>
  
    <h3>Comments</h3>
    <?php
    if ($_SESSION['uname'])
    {// client side validation for the content done purely through html, content field marked as required and provided with the correct max character length
      echo '<form name="comment" method="post" action="comment_processing.php" >
      <p><textarea name="content" placeholder="New Comment" title="New Comment" maxlength=300 style="height: 70px; width: 600px; resize: none;" required ></textarea>
      <input type="hidden" name="album_id" value="'.$album['album_id'].'">
      <input type="submit" name="submit" value="Comment" /> </p>
      </form></p></fieldset>';
    }
    
    $stmt = $db->prepare("SELECT *, UNIX_TIMESTAMP(post_date) AS post_date  FROM comment WHERE album_id = ? ORDER BY post_date DESC");
    $stmt->execute( [$_GET['id']] );
    $comment = $stmt->fetchAll();
    
    //Set default timezone to perth time
    date_default_timezone_set('Australia/Perth');
    
    // Display results or a "no comments" message as appropriate
    if (count($comment) > 0)
    {
      // if the user is logged in profiles should be hyperlinked
      if ($_SESSION['uname'])
      {
        // Loop through results to display comments
        foreach($comment as $row)
        {
          $post_date = date('d/m/Y, h:ia',$row['post_date']);
          echo '<p><fieldset><legend><strong><a href="profile.php?user='.$row['username'].'">'.nl2br(htmlentities($row['username'])).'</a></strong> on '.$post_date;
          // section for deleting comments, not currently in use
          //if ($_SESSION['uname'] == $row['username'] || $_SESSION['level'] == 'admin')
          //{
            //echo ' | <a href="edit_thread_form.php?id='.$row['comment_id'].'">delete</a>';
          //}
          echo '</legend>'.nl2br(htmlentities($row['content'])).'</fieldset></p>';
        }
      }
      else
      { // otherwise don't show profile hyperlinks
        // Loop through results to display comments
        foreach($comment as $row)
        {
          $post_date = date('d/m/Y, h:ia',$row['post_date']);
          echo '<p><fieldset><legend><strong>'.$row['username'].'</strong> on '.$post_date.'</legend>'.nl2br(htmlentities($row['content'])).'</fieldset></p>';
        }
      }
    }
    else
    {
      echo '<p>No comments posted.</p>';
    }
    ?>
  </body>
</html>
