<?php
require 'db_connect.php';

// if user is not logged in make sure they can't attempt to post a comment
if (!$_SESSION['uname'])
{
  header('Location: index.php');
}

if (isset($_POST['submit']))
{
 
  // This array will be used to store validation error messages
  // When an error is detected, the relevant message is added to the array
  $errors = [];
  
  //testing if the content field is empty or too long
  if (strlen($_POST['content']) == 0 || strlen($_POST['content']) > 300)
  {
    $errors[] = 'Comment must not be empty, and may not contain more than 300 characters';
  }
  
  if ( !ctype_digit($_POST['album_id']) )
  {
    $errors[] = 'Invalid album_id';
  }
  
  if ($errors)
  { // Display all error messages and link back to form
    foreach ($errors as $error)
    {
      echo '<p>'.$error.'</p>';
    }
   
    echo '<a href="javascript: window.history.back()">Return to album</a>';
  }
  else
  {

    $stmt = $db->prepare("INSERT INTO comment (content, album_id, username) VALUES (?, ?, ?)");
    $result = $stmt->execute( [$_POST['content'], $_POST['album_id'], $_SESSION['uname']]);
    
    if ($result)
    { // if it worked redirect to the album the comment was posted in
      header('Location: album.php?id='.$_POST['album_id'].'');
    }
    else
    { // otherwise provide generic error message
      //echo $stmt->errorInfo()[2];
    echo '<p>Something went wrong</p>';
    echo '<p><a href="javascript: window.history.back()">Return to form</a></p>';
    }
  }
}
else
{ // Show message if the form has not been submitted
  echo '<p>Invalid form data.</p>';
  echo '<p><a href="index.php">Return to index</a></p>';
}
?>