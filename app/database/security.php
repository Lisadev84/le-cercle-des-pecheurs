<?php
$configFile = __DIR__ . '/config.local.php';
if (!file_exists($configFile)) {
    $configFile = __DIR__ . '/config.prod.php';
}

class AuthDB
{
  private PDOStatement $statementRegister;
  private PDOStatement $statementReadSession;
  private PDOStatement $statementReadUser;
  private PDOStatement $statementReadUserFromPseudo;
  private PDOStatement $statementReadUserFromEmail;
  private PDOStatement $statementCreateSession;
  private PDOStatement $statementDeleteSession;


  function __construct(private PDO $pdo)
  {
    $this->statementRegister = $pdo->prepare('INSERT INTO users VALUES( 
      DEFAULT,
      :firstname,
      :lastname, 
      :pseudo,
      :email,
      :password,
      :role
      )');

    $this->statementReadSession =  $pdo->prepare('SELECT * FROM `session` WHERE idsession=:idsession');
    $this->statementReadUser = $pdo->prepare('SELECT *FROM users WHERE id=:id');
    $this->statementReadUserFromEmail = $pdo->prepare('SELECT * FROM users WHERE email= :email');
    $this->statementCreateSession = $pdo->prepare('INSERT INTO session VALUES(
        :idsession,
        :userid
      )');

    $this->statementDeleteSession =  $pdo->prepare('DELETE FROM session WHERE idsession=:idsession');
  }


  function login(string $userId): void
  {
    $sessionId = bin2hex(random_bytes(32));
    $this->statementCreateSession->bindValue(':userid', $userId);
    $this->statementCreateSession->bindValue(':idsession', $sessionId);
    $this->statementCreateSession->execute();
    $signature = hash_hmac('sha256', $sessionId, 'SECRETSESSION');
    setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, '', '', false, true);
    setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, '', '', false, true);
    return;
  }

  function register(array $user): void
  {
    $hashedPassword = password_hash($user['password'], PASSWORD_ARGON2I);
    $this->statementRegister->bindValue(':firstname', $user['firstname']);
    $this->statementRegister->bindValue(':lastname', $user['lastname']);
    $this->statementRegister->bindValue(':pseudo', $user['pseudo']);
    $this->statementRegister->bindValue(':email', $user['email']);
    $this->statementRegister->bindValue(':password', $hashedPassword);
    $this->statementRegister->bindValue(':role', $user['role']);
    $this->statementRegister->execute();
    return;
  }

  function isPseudoUnique(string $pseudo): bool
  {
    $this->statementReadUserFromPseudo->bindValue(':pseudo', $pseudo);
    $this->statementReadUserFromPseudo->execute();
    return $this->statementReadUserFromPseudo->fetchColumn() === false;
  }

  function isLoggedin(): array | false
  {
    $sessionId = $_COOKIE['session'] ?? '';
    $signature = $_COOKIE['signature'] ?? '';
    if ($sessionId && $signature) {
      $hash = hash_hmac('sha256', $sessionId, 'SECRETSESSION');
      if (hash_equals($hash, $signature)) {
        $this->statementReadSession->bindValue(':idsession', $sessionId);
        $this->statementReadSession->execute();
        $session = $this->statementReadSession->fetch();
        if ($session) {
          $this->statementReadUser->bindValue(':id', $session['userid']);
          $this->statementReadUser->execute();
          $user = $this->statementReadUser->fetch();
        }
      }
    }
    return $user ?? false;
  }

  function logout(string $sessionId): void
  {

    $this->statementDeleteSession->bindValue(':idsession', $sessionId);
    $this->statementDeleteSession->execute();
    setcookie('session', '', time() - 1);
    setcookie('signature', '', time() - 1);
    return;
  }

  function getUserFromEmail(string $email): array
  {
    $this->statementReadUserFromEmail->bindValue(':email', $email);
    $this->statementReadUserFromEmail->execute();
    return $this->statementReadUserFromEmail->fetch();
  }
}

return new AuthDB($pdo);
