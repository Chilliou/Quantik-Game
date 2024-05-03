<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

//session_start();

$quantikUIGenerator = new QuantikUIGenerator();


if(isset($_SESSION['partie']) || isset($_SESSION['pieceSelectionnee']) ){
    $quantik = $_SESSION['partie'];
	$posPiece = $_SESSION['pieceSelectionnee'];
	$pagePosePiece = $quantikUIGenerator->getPagePosePiece($quantik, PieceQuantik::WHITE, $posPiece);
	echo $pagePosePiece;
}else{
	echo QuantikUIGenerator::getPageErreur("Erreur chargement de la partie","login.php");
}


?>