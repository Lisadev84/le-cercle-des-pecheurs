<?php
$dsn = 'mysql:host=localhost;dbname=siluremonami';
$user = 'root';
$pwd = 'X*qv#!2fmw';

try{
  $pdo = new PDO($dsn, $user, $pwd, [
    PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC

  ]);
}catch(PDOException $e) {
  throw new Exception($e->getMessage());
}

return $pdo;
