<?php

namespace Quantik2024;

abstract class AbstractGame {
    protected int $gameId;
    protected $players = [];
    public int $currentPlayer;
    public string $gameStatus;

    public function __construct($gameId) {
        $this->gameId = $gameId;
        $this->gameStatus = "Not Started";
        $this->currentPlayer = 0;
    }

    public function addPlayer(Player $player) {
        $this->players[] = $player;
    }

    public function changePlayer(){
        if($this->currentPlayer == 0)
            $this->currentPlayer = 1;
        else 
            $this->currentPlayer = 0;
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
