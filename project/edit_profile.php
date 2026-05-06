<?php
require 'db_connect.php';

// if user is logged out do not allow them to view the page
if (!$_SESSION['uname'])
{
  header('Location: index.php');
}
$stmt = $db->prepare("SELECT email, profile FROM user WHERE username = ?");
$stmt->execute( [ $_SESSION['uname'] ]);
$user = $stmt->fetch();

if (!$_SESSION['uname'])
{ // if no data is retrieved redirect to index
  header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Edit <?=nl2br(htmlentities($_SESSION['uname']))?>'s Details</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="Profile detail editing" />
    <!-- <link rel="stylesheet" type="text/css" href="placeholder.css" /> -->
    <script>     
      // This function is used to validate the form
      // It is called when the form is submitted, and returns false if an error is found
      function validateForm() {
    
        // Create a variable to refer to the form
        var form = document.create_form;
        var vali_error = [];
        
        // Tests if the new password field is less than 5 characters long if one is provided
        if (form.pword.value.length < 5 && !form.pword.value == '') {
          vali_error.push('Password must be at least 5 characters long.');
        }

        // Tests if the password and password confirmation fields do not match
        if (form.pword.value != form.pword_conf.value) {
          vali_error.push('Password does not match confirmation.');
        }
  
        // Tests if the email address is valid
        if (!/\S+@\S+\.\S+/.test(form.email.value)) {
          vali_error.push('Email is not valid.');
        }        
        
        // Tests if the access duration is empty ("Select a Duration" value)
        if (form.profile.value.length > 0 && !/\S/.test(form.profile.value) ) {
          vali_error.push('Biography cannot only contain whitespace.');
        }
        
        // Tests if the profile is longer than allowed
        if (form.profile.value.length > 300) {
          vali_error.push('Biography cannot be longer than 300 characters.');
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
    <h3>Edit <?=nl2br(htmlentities($_SESSION['uname']))?>'s Details</h3>
    <form name="create_form" method="post" action="edit_profile_processing.php" onsubmit="return validateForm()">

      <fieldset><legend>User Credentials</legend>
        <p><input type="password" name="cur_pword" placeholder="Current Password" title="Current Password" required /></p>
        <p>
          <input type="password" name="pword" placeholder="New Password" title="New Password" autocomplete=off />
          <input type="password" name="pword_conf" placeholder="Confirm password" title="Confirm password" autocomplete=off />
        </p>
        <p>Leave new password fields blank to keep current password</p>
      </fieldset>
  
      <fieldset><legend>Other Details</legend>
        <p>
          <input type="email" name="email" placeholder="Email address" title="Email address" value="<?=nl2br(htmlentities($user['email']))?>" autocomplete=off required />
        </p>
        <p><textarea name="profile" placeholder="Profile Biography (optional)" title="Profile Biography (optional)" maxlength=300 style="height: 100px; width: 500px; resize: none;"><?=nl2br(htmlentities($user['profile']))?></textarea></p>

        <input type="submit" name="submit" value="Submit" class="middle" />
      </fieldset>
    </form>
	<p><a href="javascript:history.back()">Go back</a></p>
  </body>
</html>