<?php
require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';
$articleDB = require_once __DIR__ . '/database/models/ArticleDB.php';

$articles = [];
$currentUser = $authDB->isLoggedin();
if (!$currentUser) {
  header('Location: /index.php');
}

$articles = $articleDB->fetchUserArticle($currentUser['id']);

?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <?php require_once 'includes/head.php' ?>
  <link rel="stylesheet" href="/public/css/profile.css">
  <title>Mon profil</title>
  <script>
    function confirmDeletion(event, articleId) {
      event.preventDefault();
      if (confirm("Voulez-vous vraiment supprimer cet article ?")) {
        window.location.href = '/delete-article.php?id=' + articleId;
      }
    }
  </script>
</head>

<body>
  <div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
      <h1>Mon espace</h1>
      <h2>Mes informations</h2>
      <div class="info-container">
        <ul>
          <li>
            <strong>Prénom :</strong>
            <p><?= $currentUser['firstname'] ?></p>
          </li>
          <li>
            <strong>Nom :</strong>
            <p><?= $currentUser['lastname'] ?></p>
          </li>
          <li>
            <strong>Pseudo :</strong>
            <p><?= $currentUser['pseudo'] ?></p>
          </li>
          <li>
            <strong>Email :</strong>
            <p><?= $currentUser['email'] ?></p>
          </li>
          <li>
            <strong>Rôle :</strong>
            <p><?= $currentUser['role'] ?></p>
        </li>
        </ul>
      </div>
      <h2>Mes articles</h2>
      <div class="articles-list">
        <ul>
          <?php foreach ($articles as $article) : ?>
            <li>
              <span><?= $article['title'] ?></span>
              <div class="article-action">
                  <a href="/form-article.php?id=<?= $article['id'] ?>" class="btn btn-primary btn-small">Modifier</a>
                  <a href="#" onclick="confirmDeletion(event, <?= $article['id'] ?>)" class="btn btn-secondary btn-small">Supprimer</a>
                </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>
</body>

</html>