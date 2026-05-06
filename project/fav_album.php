<?php
require 'db_connect.php';

// same redirect exists on page which links to this one, included just incase
if (!$_SESSION['uname'])
{
  header('Location: index.php');
}

// nothing to validate if the values were posted, only inserting forign keys so changing the hidden field will only allow the user to insert otherwise legal values (e.g the id of a different, extant, album)

if (isset($_POST['submit']))
{  
  $stmt = $db->prepare("UPDATE user SET fav_album=? WHERE username=?");
  $result = $stmt->execute( [$_POST['album_id'], $_SESSION['uname']]);
  
  if ($result)
  { // if the insertion or update works redirect the client to the album they just rated 
    header('Location: album.php?id='.$_POST['album_id'].'');
  }
  else
  {
    //echo $stmt->errorInfo()[2];
    echo '<p>Something went wrong</p>
          <p><a href="album.php?id='.$_POST['album_id'].'">Return to album</a></p>.';
  }
}
else
{ // Show message if the form has not been submitted
  echo '<p>Invalid form data.</p>
        <p><a href="index.php">Return to index</a></p>';
}
?>