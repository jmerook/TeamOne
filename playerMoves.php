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

$suspect = $_POST['suspect'];

$weapon = $_POST['weapon'];

$occupant = $db->getCellContents($row, $column, $db->getPlayersGameID($_SESSION["id"]));

function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

if(isset($occupant))
{
    //echo "someone is here";
    //TODO somone needs to write a static html page saying this isn't a valid move and it needs a button to redirect to the home.phtml page
    debug_to_console( "Test" );
}
else
{

    $db->movePlayer($row, $column, $db->getPlayersGameID($_SESSION["id"]), $_SESSION["id"] );
    debug_to_console( "room is not occupied" );
}

//todo check to see if they have made a suggestion, if so check their suggestion again the secret envelope for that game
$envelope = $db->getLastCreatedSecretEnvelope();
$room = $db->getRoomFromCoordinates($row,$column);



    //TODO handle NPC's?
    //move the suggested player to the room
    //working as intended
    $suspectId = $db->getPlayerIdBySuspect($suspect,$db->getPlayersGameID($_SESSION["id"]));

    echo "<script>console.log( 'suspectId: " . $suspectId . "' );</script>";

    $db->moveSuspect($row, $column, $db->getPlayersGameID($_SESSION["id"]), suspectId );




//if so, then go to the you win page

//else, set the next players turn, and unset the current players turn.

$db->updateGameTurnToNextPlayer($db->getPlayersGameID($_SESSION["id"]));

// then return to the gameB_board.phtml page
header('Location: ./home.phtml');






