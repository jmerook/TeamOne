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
        //get all available players that are not currently assigned to a game
        //TODO: need to add another column to the DB table for a flag so that we can determine which players are currently in a game
        $stmt = $this->pdo->query('SELECT * FROM clueless.user WHERE game IS NULL');

        //$stmt->execute();
        $list = array();

        while ($user = $stmt->fetch())

        {
            $obj = new User($user['userName'], $user['firstName'], $user['lastName'], $user['password'], $user['id']);
            array_push($list,$obj);

        }

        return $list;


    }

    public function getCurrentGames($userID)
    {

        //get games that I am assigned to

        $stmt = $this->pdo->prepare('select user.game, game_board.gameName, game_board.secretEnvelope, game_board.id from clueless.user join clueless.game_board on game_board.id = user.game where user.id = :userID');

        $stmt->execute(['userID' => $userID]);

        $list = array();

        while ($game = $stmt->fetch())

        {
            $obj = new Game($game['id'], $game['gameName'], $game['secretEnvelope'] );
            array_push($list,$obj);

        }

        return $list;
    }

    public function getGameName($gameID)
    {
        //given the game ID, return the name of the game instance

        $stmt = $this->pdo->prepare('SELECT gameName FROM clueless.game_board WHERE id = :id');

        $stmt->execute(['id' => $gameID]);

        $game = $stmt->fetch();


            return $game['gameName'];
    }


    public function getPlayersFromGame($gameID)
    {
        //return the user objects of those that are in this game instance
        $stmt = $this->pdo->prepare('select * from clueless.user where game = :gameID order by characterNumber asc;');

        $stmt->execute(['gameID' => $gameID]);

        $list = array();


        while ($user = $stmt->fetch())

        {

            //$obj = new User($user['userName'], $user['firstName'], $user['lastName'], $user['password'], $user['id']);
            array_push($list,$user);

        }



        return $list;


        return $numPlayers['count(user.game)'];
    }
    public function getPlayersPerGame($gameID)
    {
        //pass a game ID in, and query to find out how many players are currently in the game
        //return the number


        $stmt = $this->pdo->prepare('select count(user.game)
      from clueless.game_board
        left join clueless.user on ( game_board.id = user.game )
        where game_board.id = :id
        group by game_board.gameName
        order by game_board.id asc');

        $stmt->execute(['id' => $gameID]);

        $numPlayers = $stmt->fetch();


        return $numPlayers['count(user.game)'];

    }

    public function getWeaponCard($id)
    {
        //takes the $id, which is the random number used to create a secret envelope for a new game
        $stmt = $this->pdo->prepare('SELECT * FROM clueless.weapon WHERE id = :id');

        $stmt->execute(['id' => $id]);

        if($stmt->rowCount() > 0)
        {
            $weapon = $stmt->fetch();


            return $weapon['weapon'];

        }
        else
        {

            return 0;
        }
    }

    public function getSuspectCard($id)
    {
        //takes the $id, which is the random number used to create a secret envelope for a new game

        $stmt = $this->pdo->prepare('SELECT * FROM clueless.suspect WHERE id = :id');

        $stmt->execute(['id' => $id]);

        if($stmt->rowCount() > 0)
        {
            $suspect = $stmt->fetch();


            return $suspect['suspect'];

        }
        else
        {

            return 0;
        }
    }


    public function getSuspectCardID($id)
    {
        //takes the $id, which is the random number used to create a secret envelope for a new game

        $stmt = $this->pdo->prepare('SELECT * FROM clueless.suspect WHERE id = :id');

        $stmt->execute(['id' => $id]);

        if($stmt->rowCount() > 0)
        {
            $suspect = $stmt->fetch();


            return $suspect['id'];

        }
        else
        {

            return 0;
        }
    }

    public function getCurrentPlayer($gameID)
    {
        //get the current player id for the game instance
        $stmt = $this->pdo->prepare('select * from clueless.user where game = :game and isTurn = 1');

        $stmt->execute(['game' => $gameID]);

        $user = $stmt->fetch();
        return $user['id'];
    }


    public function getRoomCard($id)
    {
        //takes the $id, which is the random number used to create a secret envelope for a new game

        $stmt = $this->pdo->prepare('SELECT * FROM clueless.room WHERE id = :id');

        $stmt->execute(['id' => $id]);

        if($stmt->rowCount() > 0)
        {
            $room = $stmt->fetch();


            return $room['room'];

        }
        else
        {

            return 0;
        }
    }

    public function getCellName($location, $game)
    {
        $row = $location[0];
        $column = $location[1];


        $stmt = $this->pdo->prepare('select * 
	      from clueless.game_map 
          where game_board = :game
          and rowNumber = :rowNumber
          and columnNumber = :columnNumber');

        $stmt->execute(['game' => $game, 'rowNumber' => $row, 'columnNumber' => $column]);


        $room = $stmt->fetch();
        return $room['roomName'];




    }

    public function getCellContents($row, $column, $game)
    {
        //see if anyone is currently in that cell, if so then return 1 instead of 0


        $stmt = $this->pdo->prepare('select * 
	      from clueless.game_map 
          where game_board = :game
          and rowNumber = :rowNum
          and columnNumber = :columnNum');

        $stmt->execute(['game' => $game, 'rowNum' => $row, 'columnNum' => $column]);


        $occupant = $stmt->fetch();
        return $occupant['occupant'];
    }


    public function movePlayer($row, $column, $game, $session)
    {

        $stmt = $this->pdo->prepare('select * 
	      from clueless.game_map 
          where game_board = :game
          and rowNumber = :rowNum
          and columnNumber = :columnNum');

        $stmt->execute(['game' => $game, 'rowNum' => $row, 'columnNum' => $column]);


        $row = $stmt->fetch();


        $stmt = $this->pdo->prepare('select user.id, user.game, user.characterNumber, game_map.rowNumber, game_map.columnNumber, game_map.roomName
	      from clueless.user 
          join clueless.game_map on game_map.game_board = user.game
          where user.id = :id
          and user.game = :game
          and game_map.occupant = user.characterNumber');

        $stmt->execute(['id' => $session, 'game' => $game]);

        $variable = $stmt->fetch();


        //these are the ones that will need to be set to null
        $oldRow = $variable['rowNumber'];
        $oldColumn = $variable['columnNumber'];

        //echo "<pre>";
        //print_r($variable);
        //echo "</pre>";

        //the row ID to update with the new player
        //$row['id']

        //this is the character number that will need to be set
        //$variable['characterNumber'];

        //this is what game we are in
        //$variable['game'];



        //update the users new position on the game map
        $stmt = $this->pdo->prepare('UPDATE clueless.game_map SET occupant= :gameCharacter WHERE id=:rowNumber');

        $stmt->execute(['gameCharacter' => $variable['characterNumber'], 'rowNumber' => $row['id']]);
        //UPDATE `clueless`.`game_map` SET `occupant`='6' WHERE `id`='404'



        //remove the old position on the game map by setting it to null
        $stmt = $this->pdo->prepare('UPDATE clueless.game_map SET occupant = null WHERE rowNumber = :oldRow and columnNumber = :oldColumn and game_board = :game');

        $stmt->execute(['oldRow' => $oldRow, 'oldColumn' => $oldColumn, 'game' => $variable['game']]);

        //UPDATE `clueless`.`game_map` SET `occupant`=null WHERE `rowNumber`='1' and columnNumber = 2



    }


    public function createGame($gameName)
    {
        $db = new Database();

        //random crime weapon card
        $varWeaponNum = rand(1,6);
        $weapon = $db->getWeaponCard($varWeaponNum);

        //random suspect card
        $varSuspectNum = rand(1,6);
        $suspect = $db->getSuspectCard($varSuspectNum);


        //random room card
        $varRoomNum = rand(1,9);
        $room = $db->getRoomCard($varRoomNum);

        //insert card into card table
        $db->createEnvelope($suspect, $weapon, $room);


        $secretEnvelope = $db->getLastCreatedSecretEnvelope();


        $stmt = $this->pdo->prepare('INSERT INTO clueless.game_board (gameName, secretEnvelope) VALUES (:gameName, :secretEnvelope)');


        $stmt->execute(['gameName' => $gameName, 'secretEnvelope' => $secretEnvelope]);



        $lastGameID = $db->getLastCreatedGame();

        $db->initiateGameMap($lastGameID);




    }

    public function getPlayersBoardPosition($id, $game)
    {


        $stmt = $this->pdo->prepare('select user.id, user.game, user.characterNumber, game_map.rowNumber, game_map.columnNumber, game_map.roomName
	      from clueless.user 
          join clueless.game_map on game_map.game_board = user.game
          where user.id = :id
          and user.game = :game
          and game_map.occupant = user.characterNumber');

        $stmt->execute(['id' => $id, 'game' => $game]);

        $variable = $stmt->fetch();


        return $variable;
    }

    public function getAvailableMovesIfRoom($row, $column)
    {
        //get the available moves for the player if they are in a room and NOT a hallway


        //the return structure will be a two digit number. the first digit is the row number and the
        //second digit will be the column number. ex if you are moving to the Study, then it would look like '11'

        //the calling method can use a method to get the text name of the room by parsing the numbers

        $moves = array();
        $obj = '';
        if ($row == 1 && $column == 1) //study
        {

            $obj = '12';
            array_push($moves, $obj);

            $obj = '21';
            array_push($moves, $obj);

            $obj = '55';    //secret passage
            array_push($moves, $obj);


        }

        elseif ($row == 1 && $column == 3) //hall
        {
            $obj = '12';
            array_push($moves, $obj);

            $obj = '23';
            array_push($moves, $obj);

            $obj = '14';
            array_push($moves, $obj);
        }

        elseif ($row == 1 && $column == 5) //Lounge
        {
            $obj = '14';
            array_push($moves, $obj);

            $obj = '25';
            array_push($moves, $obj);

            $obj = '51';    //secret passage
            array_push($moves, $obj);
        }

        elseif ($row == 3 && $column == 1) //Library
        {
            $obj = '21';
            array_push($moves, $obj);

            $obj = '32';
            array_push($moves, $obj);

            $obj = '41';
            array_push($moves, $obj);
        }

        elseif ($row == 3 && $column == 3) //Billard Room
        {
            $obj = '32';
            array_push($moves, $obj);

            $obj = '23';
            array_push($moves, $obj);

            $obj = '34';
            array_push($moves, $obj);

            $obj = '43';
            array_push($moves, $obj);
        }

        elseif ($row == 3 && $column == 5) //Dining Room
        {
            $obj = '34';
            array_push($moves, $obj);

            $obj = '25';
            array_push($moves, $obj);

            $obj = '45';
            array_push($moves, $obj);

        }

        elseif ($row == 5 && $column == 1) //Conservatory
        {
            $obj = '41';
            array_push($moves, $obj);

            $obj = '52';
            array_push($moves, $obj);

            $obj = '15'; //secret passage
            array_push($moves, $obj);

        }

        elseif ($row == 5 && $column == 3) //Ballroom
        {
            $obj = '52';
            array_push($moves, $obj);

            $obj = '43';
            array_push($moves, $obj);

            $obj = '54'; //secret passage
            array_push($moves, $obj);

        }

        elseif ($row == 5 && $column == 5) //Kitchen
        {
            $obj = '54';
            array_push($moves, $obj);

            $obj = '45';
            array_push($moves, $obj);

            $obj = '11'; //secret passage
            array_push($moves, $obj);

        }


        return $moves;
    }
    public function getAvailableMovesIfHallway($row, $column)
    {
        //get the available moves for the player if they are in a hallway and NOT a room

        //the return structure will be a two digit number. the first digit is the row number and the
        //second digit will be the column number. ex if you are moving to hallway 1, then it would look like '12'

        //the calling method can use a method to get the text name of the room by parsing the numbers

        $moves = array();
        $obj = '';

        if ($row == 1 && $column == 2) //hallway 1
        {

            $obj = '11';
            array_push($moves, $obj);

            $obj = '13';
            array_push($moves, $obj);

        }
        elseif ($row == 1 && $column == 4) //hallway 2
        {

            $obj = '13';
            array_push($moves, $obj);

            $obj = '15';
            array_push($moves, $obj);

        }
        elseif ($row == 2 && $column == 1) //hallway 3
        {

            $obj = '11';
            array_push($moves, $obj);

            $obj = '31';
            array_push($moves, $obj);

        }
        elseif ($row == 2 && $column == 3) //hallway 4
        {
            $obj = '13';
            array_push($moves, $obj);

            $obj = '33';
            array_push($moves, $obj);

        }
        elseif ($row == 2 && $column == 5) //hallway 5
        {

            $obj = '15';
            array_push($moves, $obj);

            $obj = '35';
            array_push($moves, $obj);

        }
        elseif ($row == 3 && $column == 2) //hallway 6
        {

            $obj = '31';
            array_push($moves, $obj);

            $obj = '33';
            array_push($moves, $obj);

        }
        elseif ($row == 3 && $column == 4) //hallway 7
        {

            $obj = '33';
            array_push($moves, $obj);

            $obj = '35';
            array_push($moves, $obj);

        }
        elseif ($row == 4 && $column == 1) //hallway 8
        {

            $obj = '31';
            array_push($moves, $obj);

            $obj = '51';
            array_push($moves, $obj);

        }
        elseif ($row == 4 && $column == 3) //hallway 9
        {

            $obj = '33';
            array_push($moves, $obj);

            $obj = '53';
            array_push($moves, $obj);

        }
        elseif ($row == 4 && $column == 5) //hallway 10
        {
            $obj = '35';
            array_push($moves, $obj);

            $obj = '55';
            array_push($moves, $obj);

        }
        elseif ($row == 5 && $column == 2) //hallway 11
        {

            $obj = '51';
            array_push($moves, $obj);

            $obj = '53';
            array_push($moves, $obj);

        }
        elseif ($row == 5 && $column == 4) //hallway 12
        {

            $obj = '53';
            array_push($moves, $obj);

            $obj = '55';
            array_push($moves, $obj);

        }

        return $moves;

    }

    public function setInitialGameTurn($gameID)
    {

        //get the first player (first character) and set their isTurn field to 1 for the game instance


        //$stmt = $this->pdo->prepare('UPDATE clueless.user SET isTurn = 1 WHERE game = :gameID AND characterNumber = 1');
        //$stmt = $this->pdo->prepare('UPDATE clueless.user SET isTurn= 1 WHERE game=:gameID AND characterNumber = 1;');
        //UPDATE `clueless`.`user` SET `isTurn`='1' WHERE `game`='84' AND 'characterNumber' = '1';

        $stmt = $this->pdo->prepare('UPDATE clueless.user SET isTurn = :isTurn WHERE game = :gameID AND characterNumber = :characterNumber');


        //is the players even added to this yet?
        //if this doesnt work, try updating on the users ID which is in the session variable
        $stmt->execute(['isTurn' => '1', 'gameID' => $gameID, 'characterNumber' => '1']);



    }

    public function updateGameTurnToNextPlayer($gameID)
    {
        //set the current players isTurn flag to null, and get the next character and set their isTurn to 1
        #get the characterNumber from the player who's turn it is currently (so that you can delete it later)

        $stmt = $this->pdo->prepare('select *
            from clueless.user
            where game = :gameID
            and isTurn = 1
            order by characterNumber');

        $stmt->execute(['gameID' => $gameID]);

        $oldUser = $stmt->fetch();


        //this is the old character number
        //$oldUser['characterNumber'];

        #update old player to remove isTurn so that its not their turn anymore.


        $stmt = $this->pdo->prepare('UPDATE clueless.user SET isTurn = null WHERE game = :gameID and isTurn = 1');

        $stmt->execute(['gameID' => $gameID]);


        #if characterNumber == 6, next player is 1 else its characterNumber ++ ..update their row with the turn
        $stmt = $this->pdo->prepare('UPDATE clueless.user SET isTurn = 1 WHERE game = :gameID AND characterNumber = :newCharacterNumber');


        $newCharacternumber = '';
        if($oldUser['characterNumber'] == 6)
        {
            //will need to start over if it is the last player in that game instance
            $newCharacternumber = 1;
        }
        else
        {
            //cycle through per normal
            $newCharacternumber  = $oldUser['characterNumber'] + 1;
        }

        $stmt->execute(['gameID' => $gameID, 'newCharacterNumber' => $newCharacternumber]);

    }


    public function getPlayersGameID($userID)
    {//get the ID of the game that the user is currently in

        $stmt = $this->pdo->prepare('SELECT game from clueless.user where id = :userID');

        $stmt->execute(['userID' => $userID]);

        $userGameID = $stmt->fetch();


        return $userGameID['game'];

    }

    public function initiateGameMap($id)
    {

        $stmt = $this->pdo->prepare('INSERT INTO clueless.game_map (rowNumber, columnNumber, roomName, game_board) VALUES (:rowNumber, :columnNumber, :roomName, :game_board)');

        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '1', 'roomName' => 'Study', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '2', 'roomName' => 'hallway 1', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '3', 'roomName' => 'Hall', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '4', 'roomName' => 'hallway 2', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '5', 'roomName' => 'Lounge', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '2', 'columnNumber' => '1', 'roomName' => 'hallway 3', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '2', 'columnNumber' => '3', 'roomName' => 'hallway 4', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '2', 'columnNumber' => '5', 'roomName' => 'hallway 5', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '1', 'roomName' => 'Library', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '2', 'roomName' => 'hallway 6', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '3', 'roomName' => 'Billard Room', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '4', 'roomName' => 'hallway 7', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '5', 'roomName' => 'Dining Room', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '4', 'columnNumber' => '1', 'roomName' => 'hallway 8', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '4', 'columnNumber' => '3', 'roomName' => 'hallway 9', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '4', 'columnNumber' => '5', 'roomName' => 'hallway 10', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '1', 'roomName' => 'Conservatory', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '2', 'roomName' => 'hallway 11', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '3', 'roomName' => 'Ballroom', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '4', 'roomName' => 'hallway 12', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '5', 'roomName' => 'Kitchen', 'game_board' => $id]);



        $stmt = $this->pdo->prepare('UPDATE clueless.game_map SET occupant = :occupant WHERE game_board = :id and rowNumber = :rowNumber and columnNumber = :columnNumber');
        //UPDATE clueless.game_map SET occupant='3' WHERE game_board = :$id and rowNumber = 5 and columnNumber = 2


        //set Cl. Mustard's starting spot
        $stmt->execute(['occupant' => 1, 'id' => $id, 'rowNumber' => '1', 'columnNumber' => '2']);

        //set Miss. Scarlett's starting spot
        $stmt->execute(['occupant' => 2, 'rowNumber' => '2', 'columnNumber' => '1', 'id' => $id]);

        //set Professor Plum's starting spot
        $stmt->execute(['occupant' => 3, 'rowNumber' => '5', 'columnNumber' => '2', 'id' => $id]);

        //set Mr. Green's starting spot
        $stmt->execute(['occupant' => 4, 'rowNumber' => '4', 'columnNumber' => '5', 'id' => $id]);

        //set Mrs. White's starting position
        $stmt->execute(['occupant' => 5, 'rowNumber' => '2', 'columnNumber' => '5', 'id' => $id]);

        //set Ms. Peacock's starting spot
        $stmt->execute(['occupant' => 6, 'rowNumber' => '5', 'columnNumber' => '4', 'id' => $id]);

    }

    public function getRowItems($gameBoard, $gameBoardRow, $gameBoardColumn)
    {
        //get the data for the row that is given the gameBoard instance number and the row number itself

        $stmt = $this->pdo->prepare('select * from clueless.game_map where game_board = :gameBoard and rowNumber = :gameBoardRow and columnNumber = :gameBoardColumn');
        //select * from clueless.game_map where game_board = 70 and rowNumber = 2;

        $stmt->execute(['gameBoard' => $gameBoard, 'gameBoardRow' => $gameBoardRow, 'gameBoardColumn' => $gameBoardColumn]);
        $row = $stmt->fetch();

        return $row;
    }


    public function createEnvelope($suspect, $weapon, $room)
    {

        $stmt = $this->pdo->prepare('INSERT INTO clueless.envelope (suspect, weapon, room) VALUES (:suspect, :weapon, :room)');


        $stmt->execute(['suspect' => $suspect, 'weapon' => $weapon, 'room' => $room]);

    }


    public function getLastCreatedGame()
    {

        $stmt = $this->pdo->query('select id from clueless.game_board order by id desc limit 1;');

        $gameID = $stmt->fetch();

        return $gameID['id'];
    }

    public function getLastCreatedSecretEnvelope()
    {

        $stmt = $this->pdo->query('select id from clueless.envelope order by id desc limit 1;');

        $envelopeID = $stmt->fetch();

        return $envelopeID['id'];

    }


    public function getLastPlayerInGame($gameID)
    {
        $stmt = $this->pdo->prepare('select * from clueless.user where game = :gameID order by characterNumber desc  limit 1');

        $stmt->execute(['gameID' => $gameID]);
        $gameID = $stmt->fetch();

        return $gameID['characterNumber'];
    }


    public function addPlayerToGame($userID, $gameID, $characterNumber)
    {
        $stmt = $this->pdo->prepare('UPDATE clueless.user SET game = :gameID, characterNumber = :characterNumber WHERE id = :userID');


        $stmt->execute(['gameID' => $gameID, 'userID' => $userID, 'characterNumber' => $characterNumber]);


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

        }
        else
        {

            return 0;
        }
    }


}
