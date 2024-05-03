<?php

namespace Quantik2024;

require_once 'QuantikUIGenerator.php';

$quantikUIGenerator = new QuantikUIGenerator();

if(isset($_SESSION['player'])){
	$pageHome = $quantikUIGenerator->getPageHome();
	echo $pageHome;
}else{
	echo QuantikUIGenerator::getPageErreur("Veuillez vous login","login.php");
}


?>