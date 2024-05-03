<?php

namespace Quantik2024;

require_once 'PlateauQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'AbstractGame.php';

class QuantikGame extends AbstractGame {
    public $plateau;
    public $piecesNoires;
    public $piecesBlanches;
    public $couleurPlayers = [];

    public function __construct($gameId, $plateau, $piecesNoires, $piecesBlanches) {
        parent::__construct($gameId);
        $this->plateau = $plateau;
        $this->piecesNoires = $piecesNoires;
        $this->piecesBlanches = $piecesBlanches;
    }

    public function getWhitePieces(){
        return $this->piecesBlanches;
    }

    public function getBlackPieces(){
        return $this->piecesNoires;
    }

    public function getPlateau(){
        return $this->plateau;
    }
}

?>