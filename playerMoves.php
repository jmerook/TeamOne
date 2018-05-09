<?php
//ini_set('display_errors',"1");
session_start();
include "Database.php";
include "User.php";
include "Game.php";

$db = new Database();


$move = $_POST['move'];
$row = $move[0];
$column = $move[1];

$occupant = $db->getCellContents($row, $column, $db->getPlayersGameID($_SESSION["id"]));


if(isset($occupant))
{
    //echo "someone is here";

}
else
{

    $db->movePlayer($row, $column, $db->getPlayersGameID($_SESSION["id"]), $_SESSION["id"] );

    header('Location: ./home.phtml');

}





//move the player

//check to see if they have made a suggestion, if so check their suggestion again the secret envelope for that game

//if so, then go to the you win page

//else, set the next players turn, and unset the current players turn.
// then return to the gameB_board.phtml page


//print_r($_POST);




