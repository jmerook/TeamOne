<?php
/**
 * Created by PhpStorm.
 * User: Jacob
 * Date: 4/25/18
 * Time: 11:12 PM
 */

class User
{
  private $userName;
  private $firstName;
  private $lastName;
  private $password;
  private $id;


  public function __construct($userName, $firstName, $lastName, $password, $id)
  {
      $this->userName = $userName;
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->password = $password;
      $this->id = $id;

  }

  //just an example of a setter if we need one
  public function setFirstName($newFirstName)
  {
      $this->firstName = $newFirstName;
  }

  //an example of a getter if we need one
  public function getUserName()
  {
      return $this->userName;
  }
}


//------------------------------------------------------------------------------------
//this is how you would call it in the object oriented file after you set
//the reference at the top of the page
//------------------------------------------------------------------------------------

// Create two new people
//use php funciton to call the last index of the table, then add '++' to that to add the next ID
//$User1 = new User(1 , "Bob", 34);
//$User2 = new User(2, "John", 41);

// Output their starting point
//echo "<pre>User 1: ", print_r($User1, TRUE), "</pre>";
//echo "<pre>User 2: ", print_r($User2, TRUE), "</pre>";

// Give Tom a promotion and a birthday
//$User1->changeName("Bob1");
//$User1->happyBirthday();

// John just gets a year older
//$User2->happyBirthday();

// Output the ending values
//echo "<pre>User 1: ", print_r($User1, TRUE), "</pre>";
//echo "<pre>User 2: ", print_r($User2, TRUE), "</pre>";

?>