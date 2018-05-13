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


$db = new Database();

$db->createGame($_POST["gameName"]);

$lastGameCreated = $db->getLastCreatedGame();

$i = '';

$i = 1;
foreach ($_POST['playerSelect'] as $selectedOption)
{

    //call the update method on user to set the game field to the game id that was just created
    // sort the game table by id DESC to get the ID, and add that as a dynamic variable below


    //get the characters to assign to the players. business rule based on time: the players are randomly
    //assigned - future version would allow the users to pick which characters they want.

    $characterNumber = $db->getSuspectCardID($i);


    //$db->addPlayerToGame($selectedOption, $lastGameCreated["id"]);
    $db->addPlayerToGame($selectedOption, $lastGameCreated, $characterNumber);


    $i = $i + 1;
}

//direct the page to the game board page to start the game
$db->distributeCards($lastGameCreated);

//set the first player of the game (first character) to be their turn to start the game.
$db->setInitialGameTurn($lastGameCreated);





//take the user to the game board page for the appropriate game
header('Location: ./game_board.phtml?id=' . $lastGameCreated);


