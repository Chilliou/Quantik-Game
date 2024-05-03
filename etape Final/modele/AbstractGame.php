<?php

namespace Quantik2024;

abstract class AbstractGame {

    const WHITE = 0;
    const BLACK = 1;

    public int $gameId;
    protected $players = [];
    public int $currentPlayer;
    public string $gameStatus;
	
    public function __construct() {
        $valeurMax = getrandmax();

        $this->gameStatus = "Not Started";
        $this->currentPlayer = 0;
		$this->gameId = 0;
    }

    public function addPlayer(Player $player) {
        $this->players[] = $player;
    }

    public function changePlayer(){
        $this->currentPlayer = ($this->currentPlayer == AbstractGame::WHITE) ? AbstractGame::BLACK : AbstractGame::WHITE;

    }

    public function getPlayers(){
        return $this->players;
    }

    public function getGameId(){
        return $this->gameId;
    }

    public function setGameId(int $gameId){
        $this->gameId= $gameId;
    }

    public function startGame() {
        $this->gameStatus = "In Progress";
    }

    public function endGame() {
        $this->gameStatus = "Finished";
    }

    public function getState() {
        return $this->gameStatus;
    }
}

?>
