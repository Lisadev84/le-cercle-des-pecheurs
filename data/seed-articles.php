<?php
$articles = json_decode(file_get_contents('./articles.json'), true);

$dsn = 'mysql:host=localhost;dbname=siluremonami';
$user = 'root';
$pwd = 'X*qv#!2fmw';

$pdo = new PDO($dsn, $user, $pwd);

$statement = $pdo->prepare('
INSERT INTO articles(
    title,
    image,
    content,    
    category
) VALUES (
    :title,
    :image,
    :content,
    :category
)');


foreach($articles as  $article) {
    $statement->bindValue(':title', $article['title']);
    $statement->bindValue(':image', $article['image']);
    $statement->bindValue(':content', $article['content']);
    $statement->bindValue(':category', $article['category']);
    $statement->execute();

}
