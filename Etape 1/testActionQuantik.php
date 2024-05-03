<?php

// Utilisation du namespace pour éviter les conflits de noms.
namespace Quantik2024;

// Inclusion des classes nécessaires.
require_once 'PlateauQuantik.php';
require_once 'PieceQuantik.php';
require_once 'ActionQuantik.php';


// Fonction de test pour afficher le résultat.
function displayResult($condition, $message)
{
    echo $condition ? "Test réussi: $message" : "Test échoué: $message";
    echo "<br \>";
}

// Création d'une instance de PlateauQuantik.
$plateau = new PlateauQuantik();
$actionQuantik = new ActionQuantik($plateau);

// Pose de pièces sur le plateau pour les tests non réussite.
$actionQuantik->posePiece(0, 0, PieceQuantik::initWhiteCube());
$actionQuantik->posePiece(0, 1, PieceQuantik::initWhiteCube());
$actionQuantik->posePiece(0, 2, PieceQuantik::initWhiteCylindre());
$actionQuantik->posePiece(0, 3, PieceQuantik::initWhiteSphere());

// Affichage du plateau après les poses.
echo "Affichage du plateau après poses:"."<br \>";
echo $actionQuantik;

// Tests des méthodes isRowWin, isColWin, isCornerWin.
displayResult(!$actionQuantik->isRowWin(0), 'isRowWin()');
displayResult(!$actionQuantik->isColWin(0), 'isColWin()');
displayResult(!$actionQuantik->isCornerWin(PlateauQuantik::NW), 'isCornerWin()');

// Test de la méthode isValidePose.
$pieceNoireCone = PieceQuantik::initBlackCone();
$pieceNoireCube = PieceQuantik::initBlackCube();

displayResult($actionQuantik->isValidePose(1, 1, $pieceNoireCone), 'isValidePose() pour une pose valide');
displayResult(!$actionQuantik->isValidePose(3, 0, $pieceNoireCube), 'isValidePose() pour une pose invalide'); 

// Test de la méthode posePiece avec une piece de l'adversaire sur la colonne.
$actionQuantik->posePiece(3, 0, PieceQuantik::initBlackSphere());
$actionQuantik->posePiece(3, 1, PieceQuantik::initBlackCylindre());
$actionQuantik->posePiece(3, 2, PieceQuantik::initBlackCone());
$actionQuantik->posePiece(3, 3, PieceQuantik::initBlackCube());
echo "Affichage du plateau après pose d'une pièce noire au centre:"."<br \>";
echo $actionQuantik;

// Tests des méthodes isRowWin.
displayResult($actionQuantik->isRowWin(3), 'isRowWin()');


?>
