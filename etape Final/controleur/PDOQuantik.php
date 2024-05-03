<?php

;namespace Quantik2024;
require_once '../modele/Player.php';

use PDO;
use PDOStatement;
use Quantik2024\Player;


class PDOQuantik
{
    private static PDO $pdo;

    public static function initPDO(string $sgbd, string $host, string $db, string $user, string $password): void
    {
        switch ($sgbd) {
            case 'pgsql':
                self::$pdo = new PDO('pgsql:host=' . $host . ' dbname=' . $db . ' user=' . $user . ' password=' . $password);
                break;
			case 'mysql':
			    //self::$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8', $user, $password);
				//self::$pdo = new PDO('mysql:host=localhost;dbname=quantik;charset=utf8','root','');
                self::$pdo = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$password);
				//self::$pdo = new PDO('mysql:host=' . $host . ' dbname=' . $db . ' user=' . $user . ' password=' . $password);
                break;
            default:
                exit ("Type de sgbd non correct : $sgbd fourni, 'mysql' ou 'pgsql' attendu");
        }

        // pour récupérer aussi les exceptions provenant de PDOStatement
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /* requêtes Préparées pour l'entitePlayer */
    private static PDOStatement $createPlayer;
    private static PDOStatement $selectPlayerByName;
    private static PDOStatement $selectAllPlayers;
	private static PDOStatement $selectPlayerElo;
	private static PDOStatement $updateEloPlayer;

    /******** Gestion des requêtes relatives à Player *************/
    public static function createPlayer(string $name): Player
    {
        if (!isset(self::$createPlayer))
            self::$createPlayer = self::$pdo->prepare('INSERT INTO Player(name) VALUES (:name)');
        self::$createPlayer->bindValue(':name', $name, PDO::PARAM_STR);
        self::$createPlayer->execute();
        return self::selectPlayerByName($name);
    }

    public static function selectPlayerByName(string $name): ?Player
    {
        if (!isset(self::$selectPlayerByName))
            self::$selectPlayerByName = self::$pdo->prepare('SELECT * FROM Player WHERE name=:name');
        self::$selectPlayerByName->bindValue(':name', $name, PDO::PARAM_STR);
        self::$selectPlayerByName->execute();
        $player = self::$selectPlayerByName->fetchObject('Quantik2024\Player');
        return ($player) ? $player : null;
    }

    public static function getAllPlayers(): array
    {
        if (!isset(self::$selectAllPlayers)) {
            self::$selectAllPlayers = self::$pdo->prepare('SELECT * FROM Player ORDER BY elo DESC');
        }
        self::$selectAllPlayers->execute();
        // Fetch all players as objects of the Player class
        return self::$selectAllPlayers->fetchAll(PDO::FETCH_ASSOC);
    }
	
	public static function selectPlayerElo(string $name): int
    {
        if (!isset(self::$selectPlayerElo))
            self::$selectPlayerElo = self::$pdo->prepare('SELECT elo FROM Player WHERE name=:name');
        self::$selectPlayerElo->bindValue(':name', $name, PDO::PARAM_STR);
        self::$selectPlayerElo->execute();
        $result = self::$selectPlayerElo->fetchColumn();
		return $result === false ? null : (int) $result;
    }
	
	public static function updateEloPlayer(string $name, int $value):void
    {
        if (!isset(self::$updateEloPlayer))
            self::$updateEloPlayer = self::$pdo->prepare('update Player set elo = :value  WHERE name=:name');
        self::$updateEloPlayer->bindValue(':name', $name, PDO::PARAM_STR);
		self::$updateEloPlayer->bindValue(':value', $value, PDO::PARAM_INT);
        self::$updateEloPlayer->execute();
    }

    /* requêtes préparées pour l'entiteGameQuantik */
    private static PDOStatement $createGameQuantik;
    private static PDOStatement $saveGameQuantik;
    private static PDOStatement $addPlayerToGameQuantik;
    private static PDOStatement $selectGameQuantikById;
    private static PDOStatement $selectAllGameQuantik;
    private static PDOStatement $selectAllGameQuantikByPlayerName;
	private static PDOStatement $selectAllGameQuantikInSearchOfPlayer;
	private static PDOStatement $selectAllGameQuantikOfPlayerInGame; 
	private static PDOStatement $selectHistoriqueByPlayer; 

    /******** Gestion des requêtes relatives à QuantikGame *************/

    /**
     * initialisation et execution de $createGameQuantik la requête préparée pour enregistrer une nouvelle partie
     */
    public static function createGameQuantik(string $playerName, string $json): void
    {
		$player = self::selectPlayerByName($playerName);
        if (!isset(self::$createGameQuantik))
            self::$createGameQuantik = self::$pdo->prepare('INSERT INTO QuantikGame(playerOne, json) VALUES (:playerId, :json)');
        self::$createGameQuantik->bindValue(':playerId', $player->getId(), PDO::PARAM_STR);
		self::$createGameQuantik->bindValue(':json', $json, PDO::PARAM_STR);

        self::$createGameQuantik->execute();
    }

    /**
     * initialisation et execution de $saveGameQuantik la requête préparée pour changer
     * l'état de la partie et sa représentation json
     */
    public static function saveGameQuantik(string $gameStatus, string $json, int $gameId): void
    {
         if (!isset(self::$saveGameQuantik)) {
			self::$saveGameQuantik = self::$pdo->prepare('UPDATE QuantikGame SET gameStatus = :gameStatus, json = :json WHERE gameId = :gameId');
		}
		self::$saveGameQuantik->bindValue(':gameStatus', $gameStatus, PDO::PARAM_STR);
		self::$saveGameQuantik->bindValue(':json', $json, PDO::PARAM_STR);
		self::$saveGameQuantik->bindValue(':gameId', $gameId, PDO::PARAM_INT);
		self::$saveGameQuantik->execute();
    }

    /**
     * initialisation et execution de $addPlayerToGameQuantik la requête préparée pour intégrer le second joueur
     */
    public static function addPlayerToGameQuantik(string $playerName, string $json, int $gameId): void
    {
        if (!isset(self::$addPlayerToGameQuantik)) {
			self::$addPlayerToGameQuantik = self::$pdo->prepare('UPDATE QuantikGame SET playerTwo = :playerTwo, json = :json, gameStatus = :gameStatus WHERE gameId = :gameId AND playerTwo IS NULL');
		}
		$player = self::selectPlayerByName($playerName);
		self::$addPlayerToGameQuantik->bindValue(':playerTwo', $player->getId(), PDO::PARAM_INT);
		self::$addPlayerToGameQuantik->bindValue(':json', $json, PDO::PARAM_STR);
		self::$addPlayerToGameQuantik->bindValue(':gameStatus', "initialized", PDO::PARAM_STR);
		self::$addPlayerToGameQuantik->bindValue(':gameId', $gameId, PDO::PARAM_INT);
		self::$addPlayerToGameQuantik->execute();
    }

    /**
     * initialisation et execution de $selectAllGameQuantikById la requête préparée pour récupérer
     * une instance de quantikGame en fonction de son identifiant
     */
    public static function getGameQuantikById(int $gameId): array
    {
		echo "Le game id est ".$gameId;
        if (!isset(self::$selectGameQuantikById)) {
			self::$selectGameQuantikById = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE gameId = :gameId');
		}
		self::$selectGameQuantikById->bindValue(':gameId', $gameId, PDO::PARAM_INT);
		self::$selectGameQuantikById->execute();
		return self::$selectGameQuantikById->fetchAll(PDO::FETCH_ASSOC);
		//$game = self::$selectGameQuantikById->fetchObject('Quantik2024\QuantikGame');
		//return ($game) ? $game : null;
    }
    /**
     * initialisation et execution de $selectAllGameQuantik la requête préparée pour récupérer toutes
     * les instances de quantikGame
     */
    public static function getAllGameQuantik(): array
    {
        if (!isset(self::$selectAllGameQuantik)) {
			self::$selectAllGameQuantik = self::$pdo->prepare('SELECT * FROM QuantikGame');
		}
		self::$selectAllGameQuantik->execute();
		return self::$selectAllGameQuantik->fetchAll(PDO::FETCH_ASSOC);
        //return self::$selectAllGameQuantik->fetchAll(PDO::FETCH_CLASS, 'Quantik2024\QuantikGame');
    }

    /**
     * initialisation et execution de $selectAllGameQuantikByPlayerName la requête préparée pour récupérer les instances
     * de quantikGame accessibles au joueur $playerName
     * ne pas oublier les parties "à un seul joueur"
     */
    public static function getAllGameQuantikByPlayerName(string $playerName): array
    {
        if (!isset(self::$selectAllGameQuantikByPlayerName)) {
			self::$selectAllGameQuantikByPlayerName = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE playerOne = :playerId OR playerTwo = :playerId');
		}
		$player = self::selectPlayerByName($playerName);
		self::$selectAllGameQuantikByPlayerName->bindValue(':playerId', $player->getId(), PDO::PARAM_INT);
		self::$selectAllGameQuantikByPlayerName->execute();
		return self::$selectAllGameQuantikByPlayerName->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * initialisation et execution de la requête préparée pour récupérer
     * l'identifiant de la dernière partie ouverte par $playername
     */
    public static function getLastGameIdForPlayer(string $playerName): int
    {
		echo $playerName;
		$player = self::selectPlayerByName($playerName);
		$query = 'SELECT gameId FROM QuantikGame WHERE playerOne = :playerId OR playerTwo = :playerId ORDER BY gameId DESC LIMIT 1';
		$stmt = self::$pdo->prepare($query);
		$stmt->bindValue(':playerId', $player->getId(), PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchColumn();
		return $result === false ? null : (int) $result;
    }
	
	/**
     * initialisation et execution de $selectAllGameQuantikInSearchOfPlayer la requête préparée pour récupérer les instances
     * de quantikGame accessibles au joueur $playerName pour rejoindre partie en cours
     */
    public static function getAllGameQuantikInSearch(): array
    {
        if (!isset(self::$selectAllGameQuantikInSearchOfPlayer)) {
			self::$selectAllGameQuantikInSearchOfPlayer = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE  playerTwo IS NULL');
		}
		//$player = self::selectPlayerByName($playerName);
		//self::$selectAllGameQuantikInSearchOfPlayer->bindValue(':playerId', $player->getId(), PDO::PARAM_INT);
		self::$selectAllGameQuantikInSearchOfPlayer->execute();
		return self::$selectAllGameQuantikInSearchOfPlayer->fetchAll(PDO::FETCH_ASSOC);
    }
	
	/**
     * initialisation et execution de $selectAllGameQuantikOfPlayerInGame la requête préparée pour récupérer les instances
     * de quantikGame accessibles au joueur $playerName
     * ne pas oublier les parties "à un seul joueur"
     */
    public static function getAllGameQuantikByPlayerNameInGame(string $playerName): array
    {
        if (!isset(self::$selectAllGameQuantikOfPlayerInGame)) {
			self::$selectAllGameQuantikOfPlayerInGame = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE (playerOne = :playerId OR playerTwo = :playerId) AND gameStatus = :gameStatus');
		}
		$player = self::selectPlayerByName($playerName);
		self::$selectAllGameQuantikOfPlayerInGame->bindValue(':playerId', $player->getId(), PDO::PARAM_INT);
		self::$selectAllGameQuantikOfPlayerInGame->bindValue(':gameStatus', "initialized", PDO::PARAM_STR);
		self::$selectAllGameQuantikOfPlayerInGame->execute();
		return self::$selectAllGameQuantikOfPlayerInGame->fetchAll(PDO::FETCH_ASSOC);
    }
	
	/**
     * initialisation et execution de $selectHistoriqueByPlayer la requête préparée pour récupérer les instances
     * de quantikGame accessibles au joueur $playerName
     * ne pas oublier les parties "à un seul joueur"
     */
    public static function getHistoriqueByPlayer(string $playerName): array
    {
        if (!isset(self::$selectHistoriqueByPlayer)) {
			self::$selectHistoriqueByPlayer = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE (playerOne = :playerId OR playerTwo = :playerId) AND gameStatus = :gameStatus');
		}
		$player = self::selectPlayerByName($playerName);
		self::$selectHistoriqueByPlayer->bindValue(':playerId', $player->getId(), PDO::PARAM_INT);
		self::$selectHistoriqueByPlayer->bindValue(':gameStatus', "finished", PDO::PARAM_STR);
		self::$selectHistoriqueByPlayer->execute();
		return self::$selectHistoriqueByPlayer->fetchAll(PDO::FETCH_ASSOC);
    }

}
