<?php
/**
 * Created by PhpStorm.
 * User: Jacob
 * Date: 4/30/18
 * Time: 1:38 AM
 */

//include the database object class
include "Database.php";
include "User.php";
include "Game.php";


ini_set('display_errors',"1");

//need to call the method to create a secret envelope for the game so that the game board can be inserted into the game
//board table


//create a new game with the name that was supplied. the ID will auto increment

//print_r($_POST["gameName"]);

$db = new Database();

$db->createGame($_POST["gameName"],24);

$lastGameCreated = $db->getLastCreatedGame();


//TODO: insert validation to make sure there are no more than 6 people selected

foreach ($_POST['playerSelect'] as $selectedOption)
{

    //call the update method on user to set the game field to the game id that was just created
    // sort the game table by id DESC to get the ID, and add that as a dynamic variable below

    $db->addPlayerToGame($selectedOption, $lastGameCreated["id"]);


}


//direct the page to the game board page to start the game









