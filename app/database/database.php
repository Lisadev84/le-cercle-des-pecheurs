<?php

$configFile = __DIR__ . '/config.local.php';
if (!file_exists($configFile)) {
    $configFile = __DIR__ . '/config.prod.php';
}

$config = require $configFile;

$dsn = 'mysql:host=' . $config['DB_HOST'] . ';port=' . $config['DB_PORT'] . ';dbname=' . $config['DB_NAME'];
$user = $config['DB_USER'];
$pwd = $config['DB_PASSWORD'];

try{
  $pdo = new PDO($dsn, $user, $pwd, [
    PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC
  ]);
}catch(PDOException $e) {
  error_log('Erreur de connexion à la base de données : ' . $e->getMessage());
  throw new Exception('Erreur de connexion à la base de données');
}

return $pdo;
