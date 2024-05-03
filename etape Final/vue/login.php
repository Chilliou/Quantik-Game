<?php
namespace Quantik2024;
use Quantik2024\PDOQuantik;

require_once '../controleur/PDOQuantik.php';
require_once '../vue/AbstractUIGenerator.php';

session_start();

function getPageLogin(): string {
$form = '<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <title>Se connecter</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="//fonts.googleapis.com/css2?family=Jost:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../ressources/css/login.css" type="text/css" media="all" />
    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
</head>

<body>

    <section class="forms">
        <div class="container">
            <div class="logo">
                <a class="brand-logo" href="index.html">Quantik</a>
            </div>
            <div class="forms-grid">

                <div class="login">
                    <span class="fas fa-sign-in-alt"></span>
                    <strong>Accès au salon</strong>
                    <span>Identification du joueur</span>

                    <form action="'.$_SERVER['PHP_SELF'].'" method="post" class="login-form">
                        <fieldset>
                            <div class="form">
                                <div class="form-row">
                                    <span class="fas fa-user"></span>
                                    <label class="form-label" for="input">Nom</label>
                                    <input type="text" class="form-text" name="playerName">
                                </div>
                                <div class="form-row button-login">
                                  <button name="action" type="submit" class="btn btn-login">Se connecter <span
                                  class="fas fa-arrow-right"></span></button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

        </div>
    </section>

</body>

</html>';
return $form;
}

if (isset($_REQUEST['playerName'])) {
    // connexion à la base de données
    require_once '../env/db.php';
    PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
    $player = PDOQuantik::selectPlayerByName($_REQUEST['playerName']);
    if (is_null($player))
        $player = PDOQuantik::createPlayer($_REQUEST['playerName']);
    $_SESSION['player'] = $player;
    header('HTTP/1.1 303 See Other');
    header('Location: Home.php');
}
else {
  echo getPageLogin();
}
