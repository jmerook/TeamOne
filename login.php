<?php

//include the database object class
include "Database.php";
include "User.php";

//include './User.php';

ini_set('display_errors',"1");


//it is set, so query the user DB table to get the password and see if it matches what is in the field
$db = new Database();

$user = $db->getUser($_POST["email"], $_POST["password"] );


//the getUser method will return '0' if it doesn't find a user and it will return a User object if it does.
//this will check to see what is returned and take action based on what is returned.
if( is_object($user))
{
    //start the users session
    session_start();

    // to change a session variable, just overwrite it
    //$_SESSION["user"] = $user;



    $_SESSION["userName"]  = $user->getUserName();
    $_SESSION["firstName"] = $user->getFirstName();
    $_SESSION["lastName"]  = $user->getLastName();
    $_SESSION["id"]        = $user->getID();


    //echo "<pre>";
    //print_r($_SESSION);
    //echo "</pre";



    //redirect to the game board home
    header('Location: ./home.phtml');

}
else
{
    //no user found in the DB, return code 0 occurred.
    //need to send user back to the home screen.
    header('Location: ./index.html');
}













