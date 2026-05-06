<?php
require 'db_connect.php';

// same redirect exists on page which links to this one, included just incase
if ($_SESSION['level'] != 'admin')
{
  header('Location: index.php');
}

// If the request includes form data...
if (isset($_POST['submit']))
{ // Validate and process the form

  // This array will be used to store validation error messages
  // When an error is detected, the relevant message is added to the array
  $errors = [];
  
  // Splitting tracks field using various newline characters to account for different operating systems, trimming whitespace from values, then filtering out empty values in the array
  $t_tracks = array_filter(array_map('trim',preg_split('/[\r\n]+/', $_POST['tracks'])));
  
  foreach($t_tracks as $t_track)
  {// Split each track string once there are x characters behind the current one, where x is the position of the last space in the string, limtited to 2 outputs so it doesn't split every character after the space into its own string
    $pos = strrpos($t_track, " ");
    $track = array_map('trim',preg_split('/(?<=.{'.$pos.'})/', $t_track, 2));
    $tracks[] = $track;
  }
  // The following "if" statements validate the form data
  // By using separate "if" statements, we always check all of the fields,
  // rather than stopping after finding a single error
  
  // checking if the combination of album name and year already exists, only selecting album_id as the info of the matched album isn't actually releant
  $stmt = $db->prepare("SELECT album_id FROM album WHERE album_name = ? AND year = ? ;");
  $stmt->execute( [$_POST['abname'], $_POST['year']] );
  $match = $stmt->fetch();
  
  if ($match)
  {
    $errors[] = 'Album of the same name and release year already exists.';
  }
  
  // Tests if the album name field is empty
  if ($_POST['abname'] == '')
  {
    $errors[] = 'Album name is empty.';
  }

  // Tests if the artist field is empty
  if ($_POST['artist'] == '')
  {
    $errors[] = 'Aritst field is empty.';
  }
  
  // Tests if the Record label field is empty
  if ($_POST['rlabel'] == '')
  {
    $errors[] = 'Record label field is empty.';
  }

  // Tests if the year is the wrong length
  if (1940 > $_POST['year'] || $_POST['year']> date("Y"))
  {
    $errors[] = 'Release year must be between 1940 and the current year.';
  }
  if (isset($tracks))
  {
    foreach ($tracks as $track)
    {
      //tests if the track name is too long
      if (strlen($track[0]) > 100)
      {
        $errors[] = 'Track names may not be more than 100 characters long';
        break;
      }
      //tests if the duration isn't a digit or hasn't been provided
      if (!ctype_digit($track[1]) || (!$track[1]))
      {
        $errors[] = 'Track duration must be provided in seconds with no other characters';
        break;
      }
    }
  }
  
  // If the error message array contains any items, it evaluates to True
  if ($errors)
  { // Display all error messages and link back to form
    foreach ($errors as $error)
    {
      echo '<p>'.$error.'</p>';
    }
   
    echo '<a href="javascript: window.history.back()">Return to form</a>';
  }
  else
  { // Passed validation

    // if user is editing an existing ablum (not currently in use)
    if (isset($_POST['album_id']))
    {
      $stmt = $db->prepare("UPDATE album SET album_name=?, year=?, artist=?, record_label=? WHERE album_id=?");
      $result = $stmt->execute( [$_POST['abname'], $_POST['year'], $_POST['artist'], $_POST['rlabel'], $_POST['album_id']]);
      
      if ($result)
      {// redirect to the album which was edited
        header('Location: album.php?id='.$_POST['album_id']);
      }
    }
    
    // otherwise insert a new album 
    else
    {
      $stmt = $db->prepare("INSERT INTO album (album_name, year, artist, record_label) VALUES (?, ?, ?, ?)");
      $result = $stmt->execute( [$_POST['abname'], $_POST['year'], $_POST['artist'], $_POST['rlabel']]);
      
      // if album is inserted correctly insert tracks and then redirect
      if ($result)
      {
        //saving the album id so it can be used to insert multiple tracks and redirect
        $new_album_id = $db->lastInsertId();
        
        //logging that the album has been created
        $e_type = 'Album Added';
        $e_detail = $_POST['abname'].' ('.$_POST['year'].') added by '.$_SESSION['uname'];
        $log = $db->prepare("INSERT INTO event_log (type, ip_adr, details) VALUES (?, ?, ?)");
        $log->execute( [ $e_type, $_SERVER['REMOTE_ADDR'], $e_detail ]);
        
        if (isset($tracks))
        {
          $stmt = $db->prepare("INSERT INTO track (album_id, track_name, duration) VALUES (?, ?, ?)");
        
          // for each track, run the prepared statement, success of track insertion not validated due to no tangible benifit with album already inserted
          foreach ($tracks as $track)
          {
            $stmt->execute( [$new_album_id, $track[0], $track[1]] );
          }
          
          $e_type = 'Tracks Added';
          $e_detail = count($tracks).' tracks added for '.$_POST['abname'].' ('.$_POST['year'].')';
          $log = $db->prepare("INSERT INTO event_log (type, ip_adr, details) VALUES (?, ?, ?)");
          $log->execute( [ $e_type, $_SERVER['REMOTE_ADDR'], $e_detail ]);
        }
        
        header('Location: album.php?id='.$new_album_id);
        
      }
    }
    
    // since it redirect's immediatly on success no else is used to minimise duplicate code after checking the result
    //echo $stmt->errorInfo()[0];
    echo '<p>Something went wrong</p>';
    echo '<p><a href="javascript: window.history.back()">Return to form</a></p>';
  }
}

else
{ // Show message if the form has not been submitted
  echo '<p>Invalid form data.</p>';
  echo '<p><a href="index.php">Return to index</a></p>';
}
?>