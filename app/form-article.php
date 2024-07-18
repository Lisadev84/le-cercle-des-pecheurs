<?php
require_once __DIR__ . '/database/database.php';
$authDB= require_once __DIR__ . '/database/security.php';

$currentUser = $authDB->isLoggedin();
if(!$currentUser) {
    header('Location: /index.php');
}
$articleDB = require_once __DIR__ . '/database/models/ArticleDB.php';

$statementReadOne = $pdo->prepare('SELECT * FROM articles WHERE id = :id');

$category = '';
$errors = [
    'title' => '',
    'image' =>  '',
    'category' => '',
    'content' => ''
 ];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if ($id) {
    $article = $articleDB->fetchOne($id);
    if($article['author'] !== $currentUser['id'] && $currentUser['role'] !== 'admin'){
        header('Location: /index.php');
    }

    $title = $article['title'];
    $image =  $article['image'];
    $content = $article['content'];
    $category = $article['category'];
   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/utils/sanitizeForm.php';

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        if ($id) {
             $article['title'] = $title;
             $article['image'] = $image;
             $article['category'] = $category;
             $article['content'] = $content;
             $article['content'] = $content;
             $article['author'] = $currentUser['id'];
            $articleDB->updateOne($article);
        } else {
           $articleDB->createOne([
            'title' => $title,
            'image' =>  $image,
            'category' => $category,
            'content' => $content,
            'author' => $currentUser['id']
           ]);
        }
        header('Location: /index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/form-article.css">
    <title><?= $id ? 'Modifier' : 'Créer' ?> un article</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1><?= $id ? 'Modifier' : 'Ecrire' ?> un article</h1>
                <form action="/form-article.php<?= $id ? "?id=$id" : '' ?>" , method="POST">
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" value="<?= $title ?? '' ?>">
                        <?php if ($errors['title']) : ?>
                            <p class="text-danger"><?= $errors['title'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="image">Image</label>
                        <input type="text" name="image" id="image" value="<?= $image ?? '' ?>">
                        <?php if ($errors['image']) : ?>
                            <p class="text-danger"><?= $errors['image'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="category">Catégorie</label>
                        <select name="category" id="category">
                            <?php require_once __DIR__ . "/utils/formatOfCategory.php" ?>
                        </select>
                        <?php if ($errors['category']) : ?>
                            <p class="text-danger"><?= $errors['category'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="content">Contenu</label>
                        <textarea name="content" id="content"><?= $content ?? '' ?></textarea>
                        <?php if ($errors['content']) : ?>
                            <p class="text-danger"><?= $errors['content'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="action">
                        <a href="/index.php" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary" type="submit"><?= $id ? 'Modifier' : 'Sauvegarder' ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
</body>

</html>