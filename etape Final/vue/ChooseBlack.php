<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

$quantikUIGenerator = new QuantikUIGenerator();

// Création d'une instance de PlateauQuantik et de QuantikUIGenerator.
if(isset($_SESSION['partie'])){
    $quantik = $_SESSION['partie'];
	$pageSelectionPiece = $quantikUIGenerator->getPageSelectionPiece($quantik, AbstractGame::BLACK);
	echo $pageSelectionPiece;
}else{
	echo QuantikUIGenerator::getPageErreur("Erreur chargement de la partie","login.php");
}




?>