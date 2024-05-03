<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

//session_start();


// Création d'une instance de PlateauQuantik et de QuantikUIGenerator.
$quantikUIGenerator = new QuantikUIGenerator();

if(isset($_SESSION['partie'])){
    $quantik = $_SESSION['partie'];
	$pageSelectionPiece = $quantikUIGenerator->getPageSelectionPiece($quantik, AbstractGame::WHITE);
	echo $pageSelectionPiece;
}else{
	echo QuantikUIGenerator::getPageErreur("Erreur chargement de la partie","login.php");
}




?>
