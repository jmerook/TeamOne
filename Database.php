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

    public function getCurrentGames()
    {

        //get all available games
        $stmt = $this->pdo->query('SELECT * FROM clueless.game_board');

        //$stmt->execute();
        $list = array();

        while ($game = $stmt->fetch())

        {
            $obj = new Game($game['id'], $game['gameName'], $game['secretEnvelope'] );
            array_push($list,$obj);

        }

        return $list;
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


        //TODO get the last game ID created and then make method to create game_map table entries for that board by using that ID

        $lastGameID = $db->getLastCreatedGame();

        $db->initiateGameMap($lastGameID);






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
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '2', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '3', 'roomName' => 'Hall', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '4', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '1', 'columnNumber' => '5', 'roomName' => 'Lounge', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '2', 'columnNumber' => '1', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '2', 'columnNumber' => '3', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '2', 'columnNumber' => '5', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '1', 'roomName' => 'Library', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '2', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '3', 'roomName' => 'Billard Room', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '4', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '3', 'columnNumber' => '5', 'roomName' => 'Dining Room', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '4', 'columnNumber' => '1', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '4', 'columnNumber' => '3', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '4', 'columnNumber' => '5', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '1', 'roomName' => 'Conservatory', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '2', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '3', 'roomName' => 'Ballroom', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '4', 'roomName' => 'hallway', 'game_board' => $id]);
        $stmt->execute(['rowNumber' => '5', 'columnNumber' => '5', 'roomName' => 'Kitchen', 'game_board' => $id]);
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

        }
        else
        {

            return 0;
        }
    }
}
