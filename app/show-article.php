<?php
require_once __DIR__ . '/database/database.php';
$authDB= require_once __DIR__ . '/database/security.php';

$currentUser = $authDB->isLoggedin();
$articleDB = require_once __DIR__ . '/database/models/ArticleDB.php';
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if (!$id) {
    header('Location: /index.php');
} else {
        $article = $articleDB->fetchOne($id);
    }

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/show-article.css">
    <title>Article</title>

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
            <div class="article-container">
                <a class="article-back" href="/index.php">Retour à la liste des articles</a>
                <div class="article-cover-img" style="background-image:url(<?= $article['image'] ?>"></div>
                <h1 class="article-title"><?= $article['title'] ?></h1>
                <div class="separator"></div>
                <p class="article-content"><?= $article['content'] ?></p>
                <p class="article-author"><?= $article['pseudo'] ?></p>
                <?php if ($article['author'] === $currentUser['id'] || $currentUser['role'] === 'admin'): ?>
                    <div class="action">
                    <a href="/form-article.php?id=<?= $article['id'] ?>" class="btn btn-primary btn-small">Modifier</a>
                    <a href="#" onclick="confirmDeletion(event, <?= $article['id'] ?>)" class="btn btn-secondary btn-small">Supprimer</a>
                    </div>
                <?php endif ; ?>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
</body>

</html>