<?php

$configFile = 'config.local.php';
if (!file_exists($configFile)) {
    $configFile = 'config.prod.php';
}

$config = require $configFile;

$dsn = 'mysql:host=' . $config['DB_HOST'] . ';dbname=' . $config['DB_NAME'];
$user = $config['DB_USER'];
$pwd = $config['DB_PASSWORD'];

try{
  $pdo = new PDO($dsn, $user, $pwd, [
    PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC
  ]);
}catch(PDOException $e) {
  throw new Exception($e->getMessage());
}

return $pdo;
