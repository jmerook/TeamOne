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
          //echo "there are no users in the DB with those credentials";
          return 0; // does this work with PHP?
      }


      //------------------------

      //check to see if the username and password are correct, if so, query to DB.

      //build query to get results back that have the username
      //$result = $mysql->query("SELECT * FROM clueless.user WHERE userName = " . $email . " AND password" . $password);



  }


}


//------------------------------------------------------------------------------------
//this is how you would call it in the object oriented file after you set
//the reference at the top of the page
//------------------------------------------------------------------------------------

// Create two new people
//use php funciton to call the last index of the table, then add '++' to that to add the next ID
/*
$User1 = new User(1 , "Bob", 34);
$User2 = new User(2, "John", 41);

// Output their starting point
echo "<pre>User 1: ", print_r($User1, TRUE), "</pre>";
echo "<pre>User 2: ", print_r($User2, TRUE), "</pre>";

// Give Tom a promotion and a birthday
$User1->changeName("Bob1");
$User1->happyBirthday();

// John just gets a year older
$User2->happyBirthday();

// Output the ending values
echo "<pre>User 1: ", print_r($User1, TRUE), "</pre>";
echo "<pre>User 2: ", print_r($User2, TRUE), "</pre>";
*/

