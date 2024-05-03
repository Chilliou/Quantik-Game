<?php

// Utilisation du namespace pour éviter les conflits de noms.
namespace Quantik2024;

// Inclusion des classes nécessaires.
require_once 'ArrayPieceQuantik.php';
require_once 'PieceQuantik.php';

// Fonction de test pour afficher le résultat.
function displayResult($condition, $message)
{
    echo $condition ? "Test réussi: $message" : "Test échoué: $message";
    echo "<br \>";
}

// Création d'un ensemble de pièces noires.
$blackPieces = ArrayPieceQuantik::initPiecesNoires();

// Test de la méthode __toString().
displayResult((string)$blackPieces === "(Cu:B)(Co:B)(Cy:B)(Sp:B)(Cu:B)(Co:B)(Cy:B)(Sp:B)", '__toString()');

// Test de la méthode count().
displayResult($blackPieces->count() === 8, 'count()');

// Test de la méthode offsetExists().
displayResult($blackPieces->offsetExists(0) && !$blackPieces->offsetExists(8), 'offsetExists()');

// Test de la méthode offsetGet().
displayResult($blackPieces->offsetGet(0)->getForme() === PieceQuantik::CUBE, 'offsetGet()');

// Test de la méthode offsetSet().
$blackPieces->offsetSet(0, PieceQuantik::initWhiteSphere());
displayResult($blackPieces->offsetGet(0)->getForme() === PieceQuantik::SPHERE, 'offsetSet()');

// Test de la méthode offsetUnset().
$blackPieces->offsetUnset(0);
displayResult($blackPieces->count() === 7, 'offsetUnset()');

// Test de la méthode getPieceQuantik().
$piece = $blackPieces->getPieceQuantik(1);
displayResult($piece->getForme() === PieceQuantik::CYLINDRE, 'getPieceQuantik()');

// Test de la méthode setPieceQuantik().
$blackPieces->setPieceQuantik(1, PieceQuantik::initBlackCube());
$piece = $blackPieces->getPieceQuantik(1);
displayResult($piece->getForme() === PieceQuantik::CUBE, 'setPieceQuantik()');

// Test de la méthode addPieceQuantik().
$blackPieces->addPieceQuantik(PieceQuantik::initBlackCone());
displayResult($blackPieces->count() === 8, 'addPieceQuantik()');

// Test de la méthode removePieceQuantik().
$blackPieces->removePieceQuantik(0);
displayResult($blackPieces->count() === 7, 'removePieceQuantik()');

// Test des méthodes statiques d'initialisation.
$whitePieces = ArrayPieceQuantik::initPiecesBlanches();
displayResult((string)$whitePieces === "(Cu:W)(Co:W)(Cy:W)(Sp:W)(Cu:W)(Co:W)(Cy:W)(Sp:W)", 'initPiecesBlanches()');

// Exemple d'utilisation des interfaces ArrayAccess et Countable.
displayResult(isset($blackPieces[1]) && !isset($blackPieces[8]), 'ArrayAccess and Countable interfaces');

?>