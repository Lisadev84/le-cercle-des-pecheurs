<?php

class ArticleDB 
{
  private PDOStatement $statementCreateOne;
  private PDOStatement $statementUpdateOne;
  private PDOStatement $statementDeleteOne;
  private PDOStatement $statementReadOne;
  private PDOStatement $statementReadAll;
  private PDOStatement $statementReadUserAll;




  function __construct(private PDO $pdo)
  {
    $this->statementCreateOne =$pdo->prepare('
      INSERT INTO articles (
          title,
          image,
          content,
          category,
          author
      ) VALUES (
          :title,
          :image,
          :content,
          :category,
          :author
      )    
    ');

    $this->statementUpdateOne = $pdo->prepare('
      UPDATE articles
      SET
          title=:title,
          image=:image,
          content=:content,
          category=:category,
          author=:author
      WHERE id=:id
  ');

    $this->statementReadOne = $pdo->prepare('SELECT articles.*, users.pseudo FROM articles  LEFT JOIN users ON articles.author = users.id WHERE articles.id = :id');

    $this->statementReadAll = $pdo->prepare('SELECT articles.*, users.pseudo FROM articles LEFT JOIN users ON articles.author=users.id');
    
    $this->statementDeleteOne = $pdo->prepare('DELETE FROM articles WHERE id=:id');
    $this->statementReadUserAll = $pdo->prepare('SELECT * FROM articles WHERE author=:authorId');
  }

  public function fetchAll() : array
  {
    $this->statementReadAll->execute();
    return $this->statementReadAll->fetchAll();
  }

  public function fetchOne(int $id) : array
  {
    $this->statementReadOne->bindValue(':id', $id);
    $this->statementReadOne->execute();
     return $this->statementReadOne->fetch();

  }

  public function deleteOne(int $id) : string
  {
    $this->statementDeleteOne->bindValue(':id', $id);
    $this->statementDeleteOne->execute();
    return $id;
  }

  public function createOne($article) : array
  {
    $this->statementCreateOne->bindValue(':title', $article['title']);
    $this->statementCreateOne->bindValue(':image', $article['image']);
    $this->statementCreateOne->bindValue(':content', $article['content']);
    $this->statementCreateOne->bindValue(':category', $article['category']);
    $this->statementCreateOne->bindValue(':author', $article['author']);
    $this->statementCreateOne->execute();
    return $this->fetchOne($this->pdo->lastInsertId());
  }

  public function updateOne($article) : array
  {
    $this->statementUpdateOne->bindValue(':title', $article['title']);
    $this->statementUpdateOne->bindValue(':image', $article['image']);
    $this->statementUpdateOne->bindValue(':content', $article['content']);
    $this->statementUpdateOne->bindValue(':category', $article['category']);
    $this->statementUpdateOne->bindValue(':author', $article['author']);

    $this->statementUpdateOne->bindValue(':id', $article['id']);
    $this->statementUpdateOne->execute(); 
    return $article; 
  }
  
  public function fetchUserArticle(string $authorId) : array
  {
    $this->statementReadUserAll->bindValue(':authorId',$authorId);
    $this->statementReadUserAll->execute();
    return $this->statementReadUserAll->fetchAll();
  }
}

return new ArticleDB($pdo);

?>
