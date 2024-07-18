<?php 
require __DIR__ . '/database/database.php';
$authDB = require __DIR__ . '/database/security.php';

$currentUser = $authDB->isLoggedin();
$articleDB = require_once __DIR__ . '/database/models/ArticleDB.php';

$articles = $articleDB->fetchAll();
$categories = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';

if (count($articles)) {
    $cattmp = array_map(fn ($article) => $article['category'], $articles);
    $categories = array_reduce($cattmp, function ($acc, $cat) {
        if (isset($acc[$cat])) {
            $acc[$cat]++;
        } else {
            $acc[$cat] = 1;
        }
        return $acc;
    }, []);
   

    $articlePerCategory = array_reduce($articles, function ($acc, $article) {
        if (isset($acc[$article['category']])) {
            $acc[$article['category']][] = $article;
        } else {
            $acc[$article['category']] = [$article];
        }
        return $acc;
    }, []);
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php require_once 'includes/head.php' ?>
        <link rel="stylesheet" href="/public/css/index.css">
        <title>Accueil</title>
    </head>

    <body>
        <div class="container">
            <?php require_once 'includes/header.php' ?>
            <div class="content">
                <div class="presentation">
                    <h2>Amis pêcheurs, bienvenue !</h2>
                    <p>Bienvenue sur mon site dédié à la passion de la pêche ! Ici, nous partageons nos trucs et astuces, explorons diverses techniques de pêche, et racontons l'histoire fascinante de cette pratique ancestrale.</p>
                    <p>Que vous soyez débutant ou expert, nous avons du contenu pour tous les niveaux, avec un fort respect pour une pêche éthique et responsable. Découvrez nos articles sur le matériel indispensable, nos anecdotes captivantes et profitez de la possibilité d'ajouter vos propres articles et photos. Rejoignez notre communauté de passionnés et partagez votre amour pour la pêche !</p>

                </div>
                <div class="newsfeed-container">
                    <div class="category-container">
                        <ul class="category-container">
                            <li class=<?= $selectedCat ? '' : 'cat-active' ?>>
                                <a href="/index.php">Tous les articles <span class="small">(<?= count($articles) ?>)</span></a>
                            </li>
                            <?php foreach ($categories as $catName => $catNum) : ?>
                                <li class=<?= $selectedCat === $catName ? 'cat-active' : '' ?>>
                                    <a href="/index.php?cat=<?= $catName ?>"><?= $catName ?><span class="small">(<?= $catNum ?>)</span></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="newsfeed-content">
                        <?php if (!$selectedCat) : ?>
                            <?php foreach ($categories as $cat => $num) : ?>
                                 <h2><?= mb_convert_case($cat, MB_CASE_TITLE) ?></h2>
                                <div class="articles-container">
                                    <?php foreach ($articlePerCategory[$cat] as $article) : ?>
                                        <a href="/show-article.php?id=<?= $article['id'] ?>" class="article block">
                                            <div class="overflow">
                                                <div class="img-container" style="background-image:url(<?= $article['image'] ?>)"></div>
                                            </div>
                                            <h3><?= $article['title'] ?></h3>
                                            <?php if ($article['author']): ?>
                                            <div class= "article-author">
                                                <p><?= $article['pseudo'] ?></p></div>
                                            <?php endif ; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach;  ?>
                        <?php else : ?>
                            <h2><?= mb_convert_case($selectedCat, MB_CASE_TITLE) ?></h2>
                            <div class="articles-container">
                                <?php foreach ($articlePerCategory[$selectedCat] as $article) : ?>
                                    <a href="/show-article.php?id=<?= $article['id'] ?>" class="article block">
                                        <div class="overflow">
                                            <div class="img-container" style="background-image:url(<?= $article['image'] ?>)"></div>
                                        </div>
                                        <h3><?= $article['title'] ?></h3>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php require_once 'includes/footer.php' ?>
        </div>
    </body>
</html>