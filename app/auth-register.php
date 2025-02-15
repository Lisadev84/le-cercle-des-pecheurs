<?php
 require_once __DIR__ . '/database/database.php';
 $authDB = require_once __DIR__ . '/database/security.php';
$errors = [
  'firstname' => '',
  'lastname' => '',
  'pseudo' => '',
  'email' => '',
  'password' => '',
  'confirmpassword' => ''
 ];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once './utils/sanitizeRegister.php';
  

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
      $authDB->register([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'pseudo' => $pseudo,
        'email' => $email,
        'password' => $password
      ]);
      
        header('Location: /index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <?php require_once 'includes/head.php' ?>
  <link rel="stylesheet" href="/public/css/auth-register.css">
  <title>Inscription</title>
</head>

<body>
  <div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
      <div class="block p-20 form-container">
        <h1>Inscription</h1>
        <form action="/auth-register.php" method="POST">
          <div class="form-control">
            <label for="firstname">Prénom</label>
            <input type="text" name="firstname" id="firstname" value="<?= $firstname ?? '' ?>">
            <?php if ($errors['firstname']) : ?>
              <p class="text-danger"><?= $errors['firstname'] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <label for="lastname">Nom</label>
            <input type="text" name="lastname" id="lastname" value="<?= $lastname ?? '' ?>">
            <?php if ($errors['lastname']) : ?>
              <p class="text-danger"><?= $errors['lastname'] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <label for="pseudo">Pseudo</label>
            <input type="text" name="pseudo" id="pseudo" value="<?= $pseudo ?? '' ?>">
            <?php if ($errors['pseudo']) : ?>
              <p class="text-danger"><?= $errors['pseudo'] ?></p>
            <?php endif; ?>
          </div>
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
          <div class="form-control">
            <label for="confirmpassword">Confirmation du mot de passe</label>
            <input type="password" name="confirmpassword" id="confirmpassword">
            <?php if ($errors['confirmpassword']) : ?>
              <p class="text-danger"><?= $errors['confirmpassword'] ?></p>
            <?php endif; ?>
          </div>
          <div class="action">
            <a href="/index.php" class="btn btn-secondary" type="button">Annuler</a>
            <button class="btn btn-primary" type="submit">Valider</button>
          </div>
        </form>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>
</body>

</html>