<?php
require 'db_connect.php';

// same redirect exists on page which links to this one, included just incase
if (!$_SESSION['uname'])
{
  header('Location: index.php');
}

// If the request includes form data...
if (isset($_POST['submit']))
{ // Validate and process the form

  // This array will be used to store validation error messages
  // When an error is detected, the relevant message is added to the array
  $errors = [];
  
  // Generating the password has for confirming login once all passwords have been saved as hashes
  
  // checking if current password and username match database
  $stmt = $db->prepare("SELECT username, password FROM user WHERE username = ? ;");
  $stmt->execute( [ $_SESSION['uname'] ]);
  $user= $stmt->fetch();
  
  if (!password_verify($_POST['cur_pword'], $user['password']))
  {
    $errors[] = 'Incorrct password';
  }

  // only test password length if a new password is provided
  if ($_POST['pword'])
  {
    // Tests if the password field is less than 5 characters long
    if (strlen($_POST['pword']) < 5)
    {
      $errors[] = 'New password must be at least 5 characters long.';
    }
  }
  // Tests if the password and password confirmation fields do not match do even if new password is left empty since there is still a mismatch
  if ($_POST['pword'] != $_POST['pword_conf'])
  {
    $errors[] = 'New password does not match confirmation.';
  }
  
  // Tests if a valid email is provided)
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
  {
    $errors[] = 'Email is invalid.';
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
  {
    // only attempt to modify password if a new one has been provided (as field is left blank by default)
    if ($_POST['pword'])
    {
      //Only trying to hash the new password once it is confirmed a new one has been provided
      $hash = password_hash($_POST['pword'], PASSWORD_DEFAULT);
      $stmt = $db->prepare("UPDATE user SET password = ?, email = ?, profile = ? WHERE username = ? ");
      $result = $stmt->execute( [ $hash, $_POST['email'], $_POST['profile'], $_SESSION['uname'] ]);
    }
    else
    {
      $stmt = $db->prepare("UPDATE user SET email = ?, profile = ? WHERE username = ? ");
      $result = $stmt->execute( [ $_POST['email'], $_POST['profile'], $_SESSION['uname'] ]);
    }
    
    if ($result)
    { // if it works send user to their profile page
      header('Location: profile.php?user='.$_SESSION['uname']);
    }
    else if ($stmt->errorCode() == '23000')
    { // else if the the error is due to duplicate unique entries (only possible to trigger by email with this statement), inform them the email is already used
      echo '<p>Email is already in use.</p>';
      echo '<p><a href="javascript: window.history.back()">Return to form</a>.';
    }
    else
    { // otherwise provide a generic error message
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