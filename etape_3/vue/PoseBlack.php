<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

session_start();

$quantikUIGenerator = new QuantikUIGenerator();


if(isset($_SESSION['partie'])){
    $quantik = $_SESSION['partie'];
}

if(isset($_SESSION['pieceSelectionnee'])){
    $posPiece = $_SESSION['pieceSelectionnee'];
}

$pagePosePiece = $quantikUIGenerator->getPagePosePiece($quantik, PieceQuantik::BLACK, $posPiece);
echo $pagePosePiece;
?>