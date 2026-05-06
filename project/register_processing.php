<?php
require 'db_connect.php';

// same redirect exists on page which links to this one, included just incase
if ($_SESSION['uname'])
{
  header('Location: index.php');
}

// If the request includes form data...
if (isset($_POST['submit']))
{ // Validate and process the form

  // This array will be used to store validation error messages
  // When an error is detected, the relevant message is added to the array
  $errors = [];
  
  //Variables for validating age
  $current_date = new DateTime();
  $current_date->modify("-18 years");
  $dob = new DateTime($_POST["dob"]);
  
  // checking if username or email are already in use, selecting instead of attempting to insert so that it is possible to indentify what field is duplicate
  $stmt = $db->prepare("SELECT username,email FROM user WHERE username = ? OR email = ? ;");
  $stmt->execute( [$_POST['uname'], $_POST['email']] );
  
  //using fetchALL and looping through results in case username is in use by one account and email in use by another
  $users= $stmt->fetchAll();
  
  if ($users)
  {
    foreach ($users as $user)
    if ($_POST['uname'] == $user['username'])
    {
      $errors[] = 'Username is already in use.';
    }
    
    if ($_POST['email'] == $user['email'])
    {
      $errors[] = 'Email is already in use.';
    }
  }
  
  // The following "if" statements validate the form data
  // By using separate "if" statements, we always check all of the fields,
  // rather than stopping after finding a single error
  
  // Tests if password contains non-alphanumeric characters
  if (!ctype_alnum($_POST['uname']))
  {
    $errors[] = 'Username may only contain alphanumeric characters, (A-Z or 0-9).';
  }
  
  // Tests if the username is at least 5 characters
  if (strlen($_POST['uname']) < 5)
  {
    $errors[] = 'Username is less than 5 characters.';
  }
  
  // tests if the username is more than 20 characters
  if (strlen($_POST['uname']) > 20)
  {
    $errors[] = 'Username is more than than 20 characters.';
  }
   
  // Tests if the password field is less than 5 characters long
  if (strlen($_POST['pword']) < 5)
  {
    $errors[] = 'Password must be at least 5 characters long.';
  }
  
  // Tests if the password and password confirmation fields do not match
  if ($_POST['pword'] != $_POST['pword_conf'])
  {
    $errors[] = 'Password does not match confirmation.';
  }
  
  // Tests if a valid email is provided)
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
  {
    $errors[] = 'Email is invalid.';
  }
  
  // Tests if the date field is empty
  if ($dob == '')
  {
    $errors[] = 'Date of birth is empty.';
  }
  
  // Tests if the the dob is less than 14 years ago
  if ($dob > $current_date)
  {
    $errors[] = 'Age is below 14 years old.';
  }

  // Tests if the profile field is not empty and contains only whitespace
  if($_POST['profile'] && !trim($_POST['profile']))
  {
    $errors[] = 'Profile cannot only contain whitespace.';
  }
  
  // Tests if the profile is too long
  if (strlen($_POST['profile']) > 300)
  {
    $errors[] = 'Profile cannot be longer than 300 characters.';
  }
  
  // Tests if the "I agree" checkbox is unchecked (and hence not set)
  if (!isset($_POST['agree'])) 
  {
    $errors[] = 'You must agree to the terms and conditions.';
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
  { // if there are no errors, make a hash of the password and add the user to the database
    $hash = password_hash($_POST['pword'], PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO user (username, password, dob, email, profile) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute( [ $_POST['uname'], $hash, $_POST['dob'], $_POST['email'], $_POST['profile'] ]);
    
    if ($result)
    {// if it worked redirect user to index
      $e_type = 'Registration';
      $e_detail = $_POST['uname'].' registered as a member';
      $log = $db->prepare("INSERT INTO event_log (type, ip_adr, details) VALUES (?, ?, ?)");
      $log->execute( [ $e_type, $_SERVER['REMOTE_ADDR'], $e_detail ]);
      header('Location: index.php');
    }
    
    // since it redirect's immediatly on success no else required
    echo '<p>Something went wrong</p>';
    echo '<p><a href="javascript: window.history.back()">Return to form</a></p>';
  }
}
else
{ // Show message if the form has not been submitted
  echo '<p>Please register using <a href="register.php">the form</a>.</p>';
  echo '<p><a href="index.php">Go to Index</a></p>.';
}
?>