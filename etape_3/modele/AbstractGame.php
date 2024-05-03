<?php

namespace Quantik2024;

abstract class AbstractGame {

    const WHITE = 0;
    const BLACK = 1;

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
        $this->currentPlayer = ($this->currentPlayer == AbstractGame::WHITE) ? AbstractGame::BLACK : AbstractGame::WHITE;

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
