<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

// Création d'une instance de PlateauQuantik et de QuantikUIGenerator.
$quantikUIGenerator = new QuantikUIGenerator();

if(isset($_SESSION['partie'])){
    $quantik = $_SESSION['partie'];
	$couleurActive = ($quantik->currentPlayer === AbstractGame::WHITE) ? PieceQuantik::WHITE : PieceQuantik::BLACK;
	$pageVictoire = $quantikUIGenerator->getPageVictoire($quantik, $couleurActive);
	echo $pageVictoire;
}else{
	echo QuantikUIGenerator::getPageErreur("Erreur chargement de la partie","login.php");

}
	


?>