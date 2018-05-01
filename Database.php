<?php
/**
 * Created by PhpStorm.
 * User: Jacob
 * Date: 4/25/18
 * Time: 11:04 PM
 */


class Database
{

    public $user;
    public $password;
    public $db;
    public $host;
    public $port;
    public $mysql;
    public $success;
    public $dsn;
    public $opt;
    public $pdo;

    public function __construct()
  {

      $this->host = 'localhost';
      $this->db   = 'clueless';
      $this->user = 'root';
      $this->password = 'root';
      $this->charset = 'utf8';

      $this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
      $this->opt = [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES   => false,
      ];
      $this->pdo = new PDO($this->dsn, $this->user, $this->password, $this->opt);

  }


  public function getAvailablePlayers()
  {
      //get all available players
      //TODO: need to add another column to the DB table for a flag so that we can determine which players are currently in a game
      $stmt = $this->pdo->query('SELECT * FROM clueless.user');

      //$stmt->execute();
      $list = array();

      while ($user = $stmt->fetch())

      {
          $obj = new User($user['userName'], $user['firstName'], $user['lastName'], $user['password'], $user['id']);
          array_push($list,$obj);

      }

      return $list;


  }

  public function getCurrentGames()
  {

    //get all available games
      $stmt = $this->pdo->query('SELECT * FROM clueless.game_board');

      //$stmt->execute();
      $list = array();

      while ($game = $stmt->fetch())

      {
          $obj = new Game($game['id'], $game['gameName'], $game['secretEnvelop'] );
          array_push($list,$obj);

      }

      return $list;
  }

  public function createGame($gameName, $secretEnvelope)
  {
      $stmt = $this->pdo->prepare('INSERT INTO clueless.game_board (gameName, secretEnvelope) VALUES (:gameName, :secretEnvelope)');

//INSERT INTO `clueless`.`game_board` (`gameName`, `secretEnvelope`) VALUES ('enemy', '4');
//INSERT INTO `clueless`.`game_board` (`gameName`, `secretEnvelope`) VALUES ('moreFoes', '5');
      $stmt->execute(['gameName' => $gameName, 'secretEnvelope' => $secretEnvelope]);



  }

  public function getLastCreatedGame()
  {

      $stmt = $this->pdo->query('select id from clueless.game_board order by id desc limit 1;');

      $gameID = $stmt->fetch();

      return $gameID;
  }

  public function addPlayerToGame($userID, $gameID)
  {
      //$stmt = $this->pdo->prepare('SELECT * FROM clueless.user WHERE userName = :userName AND password=:password');
      $stmt = $this->pdo->prepare('UPDATE clueless.user SET game = :gameID WHERE id = :userID');
      //UPDATE `clueless`.`user` SET `game`='2' WHERE `id`='0';


      $stmt->execute(['gameID' => $gameID, 'userID' => $userID]);




  }

  public function getUser($userName, $password)
  {
      //$stmt = $this->$pdo->query('SELECT name FROM users');
      //$stmt = $this->pdo->query('SELECT * FROM clueless.user ');
      $stmt = $this->pdo->prepare('SELECT * FROM clueless.user WHERE userName = :userName AND password=:password');

      $stmt->execute(['userName' => $userName, 'password' => $password]);

      if($stmt->rowCount() > 0)
      {
          $user = $stmt->fetch();


          $userObj = new User($user['userName'], $user['firstName'], $user['lastName'], $user['password'], $user['id']);

          //print_r($userObj);

          return $userObj;
          //create a user object and return it




          //return $userObj;


          /*
          echo $user['userName'];
          echo "<br />";
          echo $user['firstName'];
          echo "<br />";
          echo $user['lastName'];
          echo "<br />";
          echo $user['password'];
          echo "<br />";
          echo $user['id'];
          echo "<br />";
          */

          /*
           while ($row = $stmt->fetch())
          {
              echo $row['userName'];
              echo "&nbsp;";
              echo $row['firstName'];
              echo "&nbsp;";
              echo $row['lastName'];
              echo "&nbsp;";
              echo $row['password'];
              echo "&nbsp;";
              echo $row['id'];
              echo "<br />";
          }
          */
      }
      else
      {

          return 0;
      }
  }
}

