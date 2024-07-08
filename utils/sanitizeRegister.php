<?php
require_once 'errors.php';


$errors = [
  'firstname' => '',
  'lastname' => '',
  'email' => '',
  'password' => '',
  'confirmpassword' => ''
 ];

 $input = filter_input_array(INPUT_POST, [
  'firstname' => FILTER_SANITIZE_SPECIAL_CHARS,
  'lastname' => FILTER_SANITIZE_SPECIAL_CHARS,
  'email'=> FILTER_SANITIZE_EMAIL,
 ]);
 $firstname = $input['firstname'] ?? '';
 $lastname = $input['lastname'] ?? '';
 $email= $input['email'] ?? '';
 $password = $_POST['password'] ?? '';
 $confirmpassword = $_POST['confirmpassword'] ?? '';

 if(!$firstname) {
    $errors['firstname'] = ERROR_REQUIRED; 
 } elseif (mb_strlen($firstname) < 2 ) {
  $errors['firstname'] = ERROR_FIRSTNAME_TOO_SHORT;
 }

 if(!$lastname) {
  $errors['lastname'] = ERROR_REQUIRED; 
} elseif (mb_strlen($lastname) < 2 ) {
  $errors['lastname'] = ERROR_LASTNAME_TOO_SHORT;
}

if(!$email) {
  $errors['email'] = ERROR_REQUIRED; 
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors['email'] = ERROR_EMAIL_INVALID;
}

if(!$password) {
  $errors['password'] = ERROR_REQUIRED;
} elseif (mb_strlen($password) < 6 ) {
  $errors['password'] = ERROR_PASSWORD_TOO_SHORT;
}

if(!$confirmpassword) {
  $errors['password'] = ERROR_REQUIRED;
}elseif ($confirmpassword !== $password) {
  $errors['confirmpassword'] = ERROR_PASSWORD_MISMATCH;
}
