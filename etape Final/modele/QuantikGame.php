<?php

namespace Quantik2024;

require_once 'PlateauQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'AbstractGame.php';

class QuantikGame extends AbstractGame {
    public $plateau;
    public $piecesNoires;
    public $piecesBlanches;
    public $couleursPlayers = [];

    public function __construct( $plateau, $piecesNoires, $piecesBlanches, $couleursPlayers) {
        parent::__construct();
        $this->plateau = $plateau;
        $this->piecesNoires = $piecesNoires;
        $this->piecesBlanches = $piecesBlanches;
        $this->couleursPlayers = $couleursPlayers;
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
	
	public function addCouleursPlayers( $couleurPlayer)
	{
		array_push($this->couleursPlayers, $couleurPlayer);
	}
	
    public function __toString(): string
    {
		if (count($this->couleursPlayers) == 1)
            return $this->couleursPlayers[0]->getName()." en recherche";
        else
            return $this->couleursPlayers[0]->getName() . ' VS ' . $this->couleursPlayers[1]->getName();
    }
    public function getJson(): string
    {
        $json = '{';
        $json .= '"plateau":' . $this->plateau->getJson();
        $json .= ',"piecesBlanches":' . $this->piecesBlanches->getJson();
        $json .= ',"piecesNoires":' . $this->piecesNoires->getJson();
        $json .= ',"currentPlayer":' . $this->currentPlayer;
        $json .= ',"gameID":' . $this->getGameId();
        $json .= ',"gameStatus":' . json_encode($this->gameStatus);
		print_r($this->couleursPlayers);
        if (count($this->couleursPlayers) == 1)
            $json .= ',"couleursPlayers":[' . $this->couleursPlayers[0]->getJson() . ']';
        else
            $json .= ',"couleursPlayers":[' . $this->couleursPlayers[0]->getJson() . ',' . $this->couleursPlayers[1]->getJson() . ']';
        return $json . '}';
    }
    public static function initQuantikGame(string $json): QuantikGame
    {
        $object = json_decode($json);
        $players = [];
        foreach ($object->couleursPlayers as $stdObj) {
            $p = new Player();
            $p->setName($stdObj->name);
            $p->setId($stdObj->id);
            $players[] = $p;
        }
        $qg = new QuantikGame(PlateauQuantik::initPlateauQuantik($object->plateau),
                              ArrayPieceQuantik::initArrayPieceQuantik($object->piecesNoires),
                              ArrayPieceQuantik::initArrayPieceQuantik($object->piecesBlanches),
                              $players);
        //$qg->plateau = PlateauQuantik::initPlateauQuantik($object->plateau);
        //$qg->piecesBlanches = ArrayPieceQuantik::initArrayPieceQuantik($object->piecesBlanches);
        //$qg->piecesNoires = ArrayPieceQuantik::initArrayPieceQuantik($object->piecesNoires);
        $qg->currentPlayer = $object->currentPlayer;
        $qg->setGameId($object->gameID);
        $qg->gameStatus = $object->gameStatus;
        return $qg;
    }
}

?>