<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

session_start();

// Création d'une instance de PlateauQuantik et de QuantikUIGenerator.
$blackPieces = ArrayPieceQuantik::initPiecesNoires();
$whitePieces = ArrayPieceQuantik::initPiecesBlanches();
$plateau = new PlateauQuantik();
$quantikUIGenerator = new QuantikUIGenerator();

$quantik = new QuantikGame(121215,$plateau,$blackPieces,$whitePieces);
if(isset($_SESSION['partie'])){
    $quantik = $_SESSION['partie'];
}else{
    $_SESSION['partie'] = $quantik;
}

if(isset($_SESSION['pieceSelectionnee'])){
    $posPiece = $_SESSION['pieceSelectionnee'];
}

$couleurActive = ($quantik->currentPlayer === AbstractGame::WHITE) ? PieceQuantik::WHITE : PieceQuantik::BLACK;

$pageVictoire = $quantikUIGenerator->getPageVictoire($quantik, $couleurActive);
echo $pageVictoire;

?>