<?php

// Utilisation du namespace pour éviter les conflits de noms.
namespace Quantik2024;

// Inclusion de la classe à tester.
require_once 'PieceQuantik.php';

// Fonction de test pour afficher le résultat.
function displayResult($condition, $message)
{
    echo $condition ? "Test réussi: $message" : "Test échoué: $message";
    echo "<br \>";
}

// Test des méthodes statiques d'initialisation.
$voidPiece = PieceQuantik::initVoid();
displayResult($voidPiece->getForme() === 0 && $voidPiece->getCouleur() === 0, 'initVoid()');

$whiteCubePiece = PieceQuantik::initWhiteCube();
displayResult($whiteCubePiece->getForme() === 1 && $whiteCubePiece->getCouleur() === 0, 'initWhiteCube()');

$blackConePiece = PieceQuantik::initBlackCone();
displayResult($blackConePiece->getForme() === 2 && $blackConePiece->getCouleur() === 1, 'initBlackCone()');

$whiteCylindrePiece = PieceQuantik::initWhiteCylindre();
displayResult($whiteCylindrePiece->getForme() === 3 && $whiteCylindrePiece->getCouleur() === 0, 'initWhiteCylindre()');

$blackSpherePiece = PieceQuantik::initBlackSphere();
displayResult($blackSpherePiece->getForme() === 4 && $blackSpherePiece->getCouleur() === 1, 'initBlackSphere()');

// Test de la méthode __toString().
displayResult((string)$voidPiece === '(&nbsp;&nbsp;&nbsp;&nbsp;)', '__toString() for Void Piece');

// Test de la méthode __toString().
displayResult((string)$whiteCubePiece === '(Cu:W)', '__toString() for White Cube Piece');

// Test de la méthode __toString().
displayResult((string)$blackConePiece === '(Co:B)', '__toString() for Black Cone Piece');

// Test de la méthode __toString().
displayResult((string)$whiteCylindrePiece === '(Cy:W)', '__toString() for White Cylindre Piece');

// Test de la méthode __toString().
displayResult((string)$blackSpherePiece === '(Sp:B)', '__toString() for Black Sphere Piece');

?>
