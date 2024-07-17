<?php
require_once 'errors.php';


$errors = [
  'email' => '',
  'password' => ''
 ];

 $input = filter_input_array(INPUT_POST, [
  'email'=> FILTER_SANITIZE_EMAIL,
 ]);

 $email= $input['email'] ?? '';
 $password = $_POST['password'] ?? '';

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


