<?php
/**
 * Created by PhpStorm.
 * User: Dexter Ryser
 * Date: 5/8/2018
 * Time: 11:09 PM
 */

include "Database.php";
include "Game.php";

session_start();
$db = new Database();
$playerGameID = $db->getPlayersGameID($_SESSION["id"]);
// Todo: Check if it's player's turn
$accusationSuspect = $_POST['suspect'];
$accusationWeapon = $_POST['weapon'];
$accusationRoom = $_POST['room'];
$numOfActivePlayers = 0;
// Todo: get user information

// Todo: get user accusations

echo "<b> Player made an accusation of: </b>" . "<br>";
echo "suspect: " . $accusationSuspect . "<br>";
echo "weapon: " . $accusationWeapon . "<br>";
echo "room: " . $accusationRoom . "<br>";
// Todo: get secret envelope
echo "<b> Secret Envelop Contains: </b>" . "<br>";
$envelope =  $db->getLastCreatedSecretEnvelopeContents();
$realSuspect = $envelope['suspect'];
$realWeapon = $envelope['weapon'];
$realRoom = $envelope['room'];
//echo "suspect card: " . $db->getSuspectCard($db->getLastCreatedSecretEnvelope())  . "<br>";
//echo "weapon card: " . $db->getWeaponCard($db->getLastCreatedSecretEnvelope())  . "<br>";
//echo "room card: " . $db->getRoomCard($db->getLastCreatedSecretEnvelope())  . "<br>";
echo"<pre>";
    print_r($realSuspect);
echo"</pre>";
echo "suspect card: " . $envelope['suspect']  . "<br>";
echo "suspect card: " . $envelope['weapon']  . "<br>";
echo "suspect card: " . $envelope['room']  . "<br>";

// Todo: compare accusation with secret envelop content
//if ($accusationSuspect == $db->getSuspectCard($db->getLastCreatedSecretEnvelope()) &&
  //  $accusationWeapon == $db->getWeaponCard($db->getLastCreatedSecretEnvelope()) &&
 //   $accusationRoom == $db->getRoomCard($db->getLastCreatedSecretEnvelope()) )
if(($accusationSuspect == $realSuspect) && ($accusationRoom == $realRoom) && ($accusationWeapon == $realWeapon))
{
    echo "<b>" . $db->getUserNameString($db->getCurrentPlayer($playerGameID))  . " has won the game!!!" . "</b><br>";
    // Update the game status
    $db->setGameStatusToFinish($playerGameID);
    // Update the user table to have other players be eliminated except the winner.
    $db->updateUserStatusTable($playerGameID, $db->getUserNameString($db->getCurrentPlayer($playerGameID)));
    //Clear the turn fields in order to prevent users from moving
    $db->clearPlayerTurnField($playerGameID);
}
else{
    $db->setPlayerStatusToEliminated($db->getCurrentPlayer($playerGameID));
    echo "<b>" . $db->getUserNameString($db->getCurrentPlayer($playerGameID)) . " has been eliminated." . "</b> <br>";

    //Determine how many players are left in the game. If there is only one player left end the game
    $list = $db->getPlayersFromGame($playerGameID);
    foreach ($list as $user)
    {
        if($user['isEliminated'] != true)
        {
            $numOfActivePlayers++;
        }

    }
    // If there is only one player left in the game. That player becomes the winner.
    if($numOfActivePlayers == 1)
    {
        $db->setGameStatusToFinish($playerGameID);
        //Clear the turn fields in order to prevent users from moving
        $db->clearPlayerTurnField($playerGameID);
    }
    else{
        // Set the next players turn, and unset the current players turn.
        $db->updateGameTurnToNextPlayer($playerGameID);
    }
    echo "num of active players: " . $numOfActivePlayers . "<br>";
}
echo "<button class="."btn btn-lg btn-primary btn-block"." onclick="."location.href='./home.phtml'"." type="."button".">Go Back Home</button>";