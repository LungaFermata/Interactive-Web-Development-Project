<?php
require 'db_connect.php';

// if user is logged in do not allow them to attempt to register
if ($_SESSION['uname'])
{
  header('Location: index.php');
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Register</title>
    <meta name="author" content="Isaac Davies" />
    <meta name="description" content="Account registration" />
    <!-- <link rel="stylesheet" type="text/css" href="placeholder.css" /> -->
    <script>     
      // This function is used to validate the form
      // It is called when the form is submitted, and returns false if an error is found
      function validateForm() {
    
        // Create a variable to refer to the form
        var form = document.create_form;
        var vali_error = [];
        var curr_date = new Date();
        var dob = new Date(form.dob.value);
        curr_date.setFullYear(curr_date.getFullYear() - 14);
        
        
        // Tests if the username field constains non alphanumeric characters
        if (/[^A-Za-z0-9]/;.test(form.uname.valuelength)) {
          vali_error.push('Username may only contain alphanumeric characters, (A-Z or 0-9).');
        }

        // Tests if the username field is empty
        if (form.uname.valuelength < 5) {
          vali_error.push('Username must be at least 5 characters.');
        }

        // Tests if the password field is less than 5 characters long
        if (form.pword.value.length < 5) {
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
        
        // Tests if the the profile contains only whitespace
        if (form.profile.value.length > 0 && !/\S/.test(form.profile.value) ) {
          vali_error.push('Biography cannot only contain whitespace.');
        }
        
        // Tests if the profile is longer than allowed
        if (form.profile.value.length > 300) {
          vali_error.push('Biography cannot be longer than 300 characters.');
        }
        
        // Tests if the date of birth is more than 14 years ago
        if (dob > curr_date) {
          vali_error.push('You must be at least 14 years old to register');
        }

        // Tests if the "I agree" checkbox is unchecked
          if (!form.agree.checked) {
          vali_error.push('You must agree to the terms and conditions.');
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
    <h3>Create Account</h3>
    <form name="create_form" method="post" action="register_processing.php" onsubmit="return validateForm()">

      <fieldset><legend>User Credentials</legend>
        <!-- autocomplete disabled on creation fields as it just makes life harder -->
        <p><input type="text" name="uname" placeholder="Username" title="Username" maxlength=20 autocomplete=off required /></p>
        <p>
           <input type="password" name="pword" placeholder="Password" title="Password" autocomplete=off required />
           <input type="password" name="pword_conf" placeholder="Confirm password" title="Confirm password" autocomplete=off required />
        </p>
  
      </fieldset>
  
        
        <p>
          <input type="email" name="email" placeholder="Email address" title="Email address" autocomplete=off required />
          <input type="date"  name="dob" title= "Date of Birth" required>
        </p>
        <p><textarea name="profile" placeholder="Profile Biography (optional)" title="Profile Biography (optional)" maxlength=300 style="height: 100px; width: 500px; resize: none;"></textarea></p>

        <label class="middle"><input type="checkbox" name="agree" /> I agree to all <a href="javascript: alert('Nobody reads this...')" required >terms and conditions</a>.</label>

        <input type="submit" name="submit" value="Submit" class="middle" />
      </fieldset>
    </form>
	<p><a href="javascript:history.back()">Go back</a></p>
  </body>
</html>