<?php
require 'db_connect.php';

If (!$_SESSION['uname'])
{ //Redirect if not logged in
  header('Location: index.php');
}

if (!isset($_GET['user']))
{ // If there is no username in the URL
  echo '<h3>No Set</h3>';
  echo '<p><a href="javascript: window.history.back()">Go Back</a>';
  echo '<p><a href="index.php">Go to Index</a>.';
  exit;  
}
// selecting all from a SQL view which contains all relevant information for the profile
$stmt = $db->prepare("SELECT * FROM profile_view WHERE username = ?");
$stmt->execute( [$_GET['user']] );
$user = $stmt->fetch();

if (!$user)
{ // If no data (no album with that ID in the database)
  echo '<h3>Bad Set</h3>';
  echo '<p><a href="javascript: window.history.back()">Go Back</a>';
  echo '<p><a href="index.php">Go to Index</a>.';
  exit;  
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?=nl2br(htmlentities($user['username']))?>'s Profile</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="View user profile" />
    <!-- <link rel="stylesheet" type="text/css" href="placeholder.css" /> -->
  </head>

  <body>
    <h1><a href="index.php">ALBVM</a></h1>
    <p>Hello <a href="profile.php?user=<?=$_SESSION['uname']?>"><?=nl2br(htmlentities($_SESSION['uname']))?></a> | <a href="edit_profile.php">Edit Profile</a> | <a href="logout_processing.php">Logout</a></p>
    <?php
    If ($_SESSION['level'] == 'admin')
    {
      echo '<p><a href="new_album.php">New album</a></p>';
    }
    ?>
    <form name="search_threads" method="get" action="search.php" >
        <p><input type="text" name="search_term" placeholder="Enter search term..." title="Search" /> <input type="submit" name="submit" value="Search" /></p>
    </form>
		<?php
    // Display the Profile details
    
    
    echo '<h2>'.nl2br(htmlentities($user['username'])).'</h2>';
    echo '<h3>Year of Birth: '.substr($user['dob'], 0,4).'</h3>';
    
    // not displaying the favourite fields if the user does not have one
    if ($user['fa_name'])
      echo '<p>Favourite Album: <a href="album.php?id='.$user['fa_album_id'].'">'.nl2br(htmlentities($user['fa_artist'])).' "'.nl2br(htmlentities($user['fa_name'])).'" ('.$user['fa_year'].')</a></p>';
    
    if ($user['ft_name'])
      echo '<p>Favourite Track: <a href="album.php?id='.$user['ft_album_id'].'">'.nl2br(htmlentities($user['ft_artist'])).' "'.nl2br(htmlentities($user['ft_name'])).'" ('.$user['ft_year'].')</a></p>';

    // still including the profile when empty to avoid complete blank space but putting placeholder text if profile is null
    if (!$user['profile'])
    {
      echo '<fieldset><legend><h3>Biography:</h3></legend>No Bio provided.</fieldset>';
    }
    else
    {
      echo '<fieldset><legend><h3>Biography:</h3></legend>'.nl2br(htmlentities($user['profile']).'</fieldset>');
    }
    ?>