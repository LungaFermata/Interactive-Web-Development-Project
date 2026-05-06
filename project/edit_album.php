<?php
require 'db_connect.php';

//Currently this page is not in use as the database structure isn't fit for any reordering of tracks
header('Location: index.php');
// if the user is not an admin or the url doesn't contain a valid album id redirect to index
If ($_SESSION['level'] != 'admin' || !isset($_GET['id']) || !ctype_digit($_GET['id'])))
{
  header('Location: index.php');
}

$stmt = $db->prepare("SELECT * FROM album WHERE album_id = ?;");
$stmt->execute( [$_GET['id']] );
$album = $stmt->fetch();

if (!$album)
{ // If no data (no album with that ID in the database)
  echo '<h3>Album Not Found</h3>';
  echo '<p><a href="javascript: window.history.back()">Go Back</a>';
  echo '<p><a href="index.php">Go to Index</a>.';
  exit;  
?>
<!DOCTYPE html>
<html>
  <head>
    <title>New Album</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="Album editing" />
    <!-- <link rel="stylesheet" type="text/css" href="placeholder.css" /> -->
    <script>     
      // This function is used to validate the form
      // It is called when the form is submitted, and returns false if an error is found
      // Not testing for empty fields as utilisng the required attribute in the html
      function validateForm() {
      
        // Create a variable to refer to the form
        var form = document.new_album_form;
        // create array for error storage
        var vali_error = [];
        
        // create variable for date validation
        var curr_year = new Date().getFullYear();
        
        // Tests if year is 4 digets
        if (!/^\d{4}$/.test(form.year.value)) {
          vali_error.push('Release year must be 4 digets');
        }
        else
        {
          // Tests if year is 1940 or later
          if (form.year.value < 1940) {
            vali_error.push('Release year must be 1940 or later');
          } 
          
          // change to current year
          if (form.year.value > curr_year) {
            vali_error.push('Release year cannot be later than the current year')
          }
        }
        
        // If any tests were failed, aleart the user to the validation errors and return false
        if (!vali_error.length == 0) {
          alert(vali_error.join('\n'));
          return false;
        }
      }
    </script>
  </head>

  <body>
    <h1><a href="index.php">ALBVM</a></h1>
    
    <!-- no validation required as user must be admin to view this page -->
    <p>Hello <a href="profile.php?user='.$_SESSION['uname'].'"><?=$_SESSION['uname']?></a>
    | <a onclick="return confirm(\'Are you sure you want to log out?\')" href="logout_processing.php">Logout</a></p>
    
    <h2>New Album</h2>
    <p>Add a new album by completing all fields.</p>
    <form name="new_album_form" method="post" action="album_processing.php" onsubmit="return validateForm()">
        
        <!-- autocomplete disabled on creation fields as it just makes life harder -->
        <p><label>Album Name: <input type="text" name="abname" placeholder="Album Name" title="Album Name" autocomplete=off required /></label></p>
        <p><label>Artist(s): <input type="text" name="artist" placeholder="Artist" title="Artist" autocomplete=off required /></label></p>
        <!-- using text type and inputmode for the year so I can actually set a max number of digets to enter, validating if it only contains digets with javascript instead -->
        <p><label>Release Year: <input type="text" inputmode="numeric" name="year" title="year" placeholder="****" style="width: 40px;" maxlength="4" autocomplete=off required /></label></p>
        <p><label>Record Label(s): <input type="text" name="rlabel" placeholder="Record Label" title="Record Label"  autocomplete=off required /></label></p>
        <h3>Tracks:</h3>
        <p>Enter each track on a new line.</p>
        <p><textarea name="tracks" placeholder="Enter album tracks in order" title="Album Tracks" style="height: 300px; width: 750px; resize: none;"></textarea></p
        <br />

        <input type="submit" name="submit" value="Submit" />
    </form>
	<p><a href="javascript:history.back()">Go back</a></p>
  </body>
</html>