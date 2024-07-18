<?php
require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';
$errors = [
  'firstname' => '',
  'lastname' => '',
  'email' => '',
  'password' => '',
  'confirmpassword' => ''
 ];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/utils/sanitizeLogin.php';
    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
      
      $user = $authDB->getUserFromEmail($email);

      if (!$user){
        $errors['email'] = ERROR_EMAIL_UNKNOW;
      } else {
        if(!password_verify($password, $user['password'])) {
          $errors['password'] = ERROR_PASSWORD_MISMATCH;
        } else {
          $authDB->login($user['id']);
          header('Location: /index.php');
        }
      }
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <?php require_once 'includes/head.php' ?>
  <link rel="stylesheet" href="/public/css/auth-login.css">
  <title>Connexion</title>
</head>

<body>
  <div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
    <div class="block p-20 form-container">
        <h1>Connexion</h1>
        <form action="/auth-login.php" method="POST">
          <div class="form-control">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= $email ?? '' ?>">
            <?php if ($errors['email']) : ?>
              <p class="text-danger"><?= $errors['email'] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password">
            <?php if ($errors['password']) : ?>
              <p class="text-danger"><?= $errors['password'] ?></p>
            <?php endif; ?>
          </div>
          <div class="action">
            <a href="/index.php" class="btn btn-secondary" type="button">Annuler</a>
            <button class="btn btn-primary" type="submit">Connexion</button>
          </div>
        </form>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>
</body>

</html>