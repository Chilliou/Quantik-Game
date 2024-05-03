<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

session_start();

$abstractUIGenerator = new AbstractUIGenerator();

// Création d'une instance de PlateauQuantik et de QuantikUIGenerator.
$blackPieces = ArrayPieceQuantik::initPiecesNoires();
$whitePieces = ArrayPieceQuantik::initPiecesBlanches();
$plateau = new PlateauQuantik();
$quantik = new QuantikGame(121215,$plateau,$blackPieces,$whitePieces);
if(isset($_SESSION['partie'])){
    $quantik = $_SESSION['partie'];
}else{
    $_SESSION['partie'] = $quantik;
}
$quantikUIGenerator = new QuantikUIGenerator();

$pageSelectionPiece = $quantikUIGenerator->getPageSelectionPiece($quantik, AbstractGame::WHITE);
echo $pageSelectionPiece;
?>
