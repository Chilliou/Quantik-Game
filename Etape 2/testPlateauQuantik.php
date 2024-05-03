<?php

// Utilisation du namespace pour éviter les conflits de noms.
namespace Quantik2024;

// Inclusion des classes nécessaires.
require_once 'PieceQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'PlateauQuantik.php';

// Fonction de test pour afficher le résultat.
function displayResult($condition, $message)
{
    echo $condition ? "Test réussi: $message" : "Test échoué: $message";
    echo "<br \>";
}

// Création d'une instance de PlateauQuantik.
$plateau = new PlateauQuantik();

// Test des méthodes de base.
displayResult($plateau->getPiece(0, 0)->getForme() === 0, 'getPiece() pour pièce vide');
displayResult($plateau->getRow(0)->count() === PlateauQuantik::NBCOLS, 'getRow()');
displayResult($plateau->getCol(0)->count() === PlateauQuantik::NBROWS, 'getCol()');

// Test de la méthode getCorner().
$cornerNW = $plateau->getCorner(PlateauQuantik::NW);
displayResult($cornerNW->count() === 4 && $cornerNW->getPieceQuantik(0)->getForme() === 0, 'getCorner(NW)');

// Test de la méthode __toString().
echo "Affichage du plateau initial:"."<br \>";
echo $plateau;

// Test de la méthode getCornerFromCoord().
displayResult(PlateauQuantik::getCornerFromCoord(0, 0) === PlateauQuantik::NW, 'getCornerFromCoord(0, 0)');
displayResult(PlateauQuantik::getCornerFromCoord(2, 3) === PlateauQuantik::SE, 'getCornerFromCoord(2, 3)');

// Test de la méthode setPiece() et __toString().
$plateau->setPiece(1, 1, PieceQuantik::initWhiteCube());
echo "Affichage du plateau après ajout d'une pièce blanche au centre:"."<br \>";
echo $plateau;

?>
