<?php
require 'db_connect.php';

// same redirect exists on page which links to this one, included just incase
if (!$_SESSION['uname'])
{
  header('Location: index.php');
}

if (isset($_POST['submit']))
{  
if (1 > [$_POST['score']] || [$_POST['score']] > 5)
  {
    echo '<p>Invalid form data.</p>
          <p><a href="index.php">Return to index</a></p>';
  }
  
  // queried in the album page to check the user's rating, simply posting a varible to track that. If posted value removed on client side it simply results in an SQL error
  if (isset($_POST['edit']))
  {
    $stmt = $db->prepare("UPDATE rating SET score=? WHERE album_id=? AND username=?");
    $result = $stmt->execute( [$_POST['score'], $_POST['album_id'], $_SESSION['uname']]);
  }
  else
  {
    $stmt = $db->prepare("INSERT INTO rating (score, album_id, username) VALUES (?, ?, ?)");
    $result = $stmt->execute( [$_POST['score'], $_POST['album_id'], $_SESSION['uname']]);
  }
  
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