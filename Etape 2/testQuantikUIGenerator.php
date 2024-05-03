<?php

// Utilisation du namespace pour éviter les conflits de noms.
namespace Quantik2024;

// Inclusion des classes nécessaires.
require_once 'PieceQuantik.php';
require_once 'PlateauQuantik.php';
require_once 'QuantikUIGenerator.php';

// Fonction de test pour afficher le résultat.
function displayResult($condition, $message)
{
    echo $condition ? "Test réussi: $message\n" : "Test échoué: $message\n";
}

session_start();


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

/*
// Test des méthodes getButtonClass, getDivPiecesDisponibles et getFormSelectionPiece.
$divPiecesDisponibles = $quantikUIGenerator->getDivPiecesDisponibles($blackPieces);
echo "Affichage des pièces disponibles:\n";
echo $divPiecesDisponibles;

// Test des méthodes getDivPlateauQuantik et getFormPlateauQuantik.
$divPlateauQuantik = $quantikUIGenerator->getDivPlateauQuantik($plateau);
$formPlateauQuantik = $quantikUIGenerator->getFormPlateauQuantik($plateau,PieceQuantik::initWhiteSphere());
echo "Affichage du plateau de jeu:\n";
echo $divPlateauQuantik;
echo $formPlateauQuantik;

// Test des méthodes getFormBoutonAnnulerChoixPiece, getDivMessageVictoire et getLienRecommencer.
$formBoutonAnnulerChoixPiece = $quantikUIGenerator->getFormBoutonAnnulerChoixPiece();
echo "Affichage du formulaire bouton annuler choix pièce:\n";
echo $formBoutonAnnulerChoixPiece;

$divMessageVictoire = $quantikUIGenerator->getDivMessageVictoire(PieceQuantik::WHITE);
echo "Affichage du message de victoire:";
echo $divMessageVictoire;

$lienRecommencer = $quantikUIGenerator->getLienRecommencer();
echo "Affichage du lien pour recommencer:\n";
echo $lienRecommencer;

*/
/////////////

// Test des méthodes getPageSelectionPiece, getPagePosePiece et getPageVictoire.
$pageSelectionPiece = $quantikUIGenerator->getPageSelectionPiece($quantik, PieceQuantik::WHITE);
echo "Affichage de la page de sélection de pièce:\n";
echo $pageSelectionPiece;
if(isset($_SESSION['pieceSelectionnee'])){
    $posPiece = $_SESSION['pieceSelectionnee'];
}
$pagePosePiece = $quantikUIGenerator->getPagePosePiece($quantik, PieceQuantik::WHITE, $posPiece);
echo "Affichage de la page de pose de pièce:\n";
echo $pagePosePiece;

$pageVictoire = $quantikUIGenerator->getPageVictoire($quantik, PieceQuantik::WHITE);
echo "Affichage de la page de victoire:\n";
echo $pageVictoire;

?>
