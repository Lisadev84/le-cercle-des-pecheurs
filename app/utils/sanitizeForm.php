<?php
require_once 'errors.php';

$articles = [];
$errors = [
    'title' => '',
    'image' =>  '',
    'category' => '',
    'content' => ''
];

$_POST = filter_input_array(INPUT_POST, [
    'title' => FILTER_SANITIZE_SPECIAL_CHARS,
    'image' => FILTER_SANITIZE_URL,
    'category' => FILTER_SANITIZE_SPECIAL_CHARS,
    'content' => [
        'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
    ]
]);

$title = $_POST['title'] ?? '';
$image = $_POST['image'] ?? '';
$category = $_POST['category'] ?? '';
$content = $_POST['content'] ?? '';
$availableCategories = require 'availableCategories.php';



if (!$title) {
    $errors['title'] = ERROR_REQUIRED;
} elseif (mb_strlen($title) < 5) {
    $errors['title'] = ERROR_TITLE_TOO_SHORT;
} elseif (mb_strlen($title) > 100) {
    $errors['title'] = ERROR_TITLE_TOO_LONG;
} elseif (!$id && array_search(mb_strtolower($title), array_map(fn ($el) => mb_strtolower($el['title']), $articles))) {
    $errors['title'] = ERROR_DOUBLE_TITLE;
}


if (!$image) {
    $errors['image'] = ERROR_REQUIRED;
} elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
    $errors['image'] = ERROR_IMAGE_URL;
} elseif ((mb_strlen($image) > 1000)) {
    $errors['image'] = ERROR_IMAGE_URL_TOO_LONG;
}

if (!$category) {
    $errors['category'] = ERROR_REQUIRED;
} elseif (!in_array($category, $availableCategories)) {
    $errors['category'] = ERROR_CATEGORY_NOT_EXIST;
}


if (!$content) {
    $errors['content'] = ERROR_REQUIRED;
} elseif (mb_strlen($content) < 50) {
    $errors['content'] = ERROR_CONTENT_TOO_SHORT;
} elseif (mb_strlen($content) > 5000) {
    $errors['content'] = ERROR_CONTENT_TOO_LONG;
}
