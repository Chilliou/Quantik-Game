<?php

namespace Quantik2024;

require_once '../modele/PlateauQuantik.php';
require_once '../modele/ArrayPieceQuantik.php';
require_once '../modele/PieceQuantik.php';
	


$piece = PieceQuantik::initWhiteCube();
echo $piece."<br>";
$jsonPiece = $piece->getJson();
echo $jsonPiece."<br>";
$piecefromjson = PieceQuantik::initPieceQuantik($jsonPiece);
echo $piecefromjson."<br>";

$array = ArrayPieceQuantik::initPiecesNoires();
echo $array."<br>";
$jsonArray = $array->getJson();
echo $jsonArray."<br>";
$arrayfromjson = ArrayPieceQuantik::initArrayPieceQuantik($jsonArray);
echo $arrayfromjson."<br>";



$plateau = new PlateauQuantik();
echo $plateau;
$jsonPlateau = $plateau->getJson();
echo $jsonPlateau;
$plateauDeJson  = PlateauQuantik::initPlateauQuantik($jsonPlateau);
echo $plateauDeJson;


?>