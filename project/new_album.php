<?php
require 'db_connect.php';

// redirect the user if they are not an admin
If ($_SESSION['level'] != 'admin')
{
  header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>New Album</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="Album creation" />
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
          // Tests if year is less than 1940
          if (form.year.value < 1940) {
            vali_error.push('Release year must be 1940 or later');
          } 
          
          // check if year is greater tha the current year
          if (form.year.value > curr_year) {
            vali_error.push('Release year cannot be later than the current year');
          }
        }
        
        // Checks if tracks were provided, if they were splits them and checks each line to see if it ends with " " followed by digets
        if (form.tracks.value.length > 0) {
          var tracks = form.tracks.value.split();
          for (const track of tracks) {
            if (!/^.* [0-9]*$/.test(track)){
              vali_error.push('Each track Line must end in its duration in seconds');
            }
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
    <p>Hello <a href="profile.php?user=<?=$_SESSION['uname']?>"><?=nl2br(htmlentities($_SESSION['uname']))?></a>
    | <a onclick="return confirm(\'Are you sure you want to log out?\')" href="logout_processing.php">Logout</a></p>
    
    <h2>New Album</h2>
    <p>Add a new album by completing all fields.</p>
    <form name="new_album_form" method="post" action="new_album_processing.php" onsubmit="return validateForm()">
        
        <!-- autocomplete disabled on creation fields as it just makes life harder -->
        <p><label>Album Name: <input type="text" name="abname" placeholder="Album Name" title="Album Name" autocomplete=off required /></label></p>
        <p><label>Artist(s): <input type="text" name="artist" placeholder="Artist" title="Artist" autocomplete=off required /></label></p>
        <!-- using text type and inputmode for the year so I can actually set a max number of digets to enter, validating if it only contains digets with javascript instead -->
        <p><label>Release Year: <input type="text" inputmode="numeric" name="year" title="Year" placeholder="****" style="width: 40px;" maxlength="4" autocomplete=off required /></label></p>
        <p><label>Record Label(s): <input type="text" name="rlabel" placeholder="Record Label" title="Record Label"  autocomplete=off required /></label></p>
        <h3>Tracks:</h3>
        <p>Enter each track on a new line.</p>
        <p><textarea name="tracks" placeholder="Enter tracks and their durations in seconds e.g.&#10;track 1 105&#10;track 2 158" title="Album Tracks" style="height: 300px; width: 750px; resize: none;"></textarea></p
        <br />

        <input type="submit" name="submit" value="Submit" />
    </form>
	<p><a href="javascript:history.back()">Go back</a></p>
  </body>
</html>