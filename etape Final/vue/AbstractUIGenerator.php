<?php

namespace Quantik2024;


class AbstractUIGenerator {
    public static function getDebutHTML($title = "title content", $css = "style.css"): string {
        return "<!DOCTYPE html>
                <html lang='fr-FR'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <link rel='stylesheet' href='../ressources/css/$css'>
                    <link href='//fonts.googleapis.com/css2?family=Jost:wght@300;400;600&display=swap' rel='stylesheet'>
                    <script src='https://kit.fontawesome.com/af562a2a63.js' crossorigin='anonymous'></script>
                    <title>$title</title>
                </head>
                <body>";
    }

    public static function getAppbarHTML(): string {
        return "<div class='appbar'>
                    <div class='appbar-centre'>
                        <h2>Quantik</h2>
                    </div>
                    <div class='appbar-droite'>
                        <a href='login.php' class='profil'>Se déconnecter</a>
                    </div>
                </div>";
    }

    public static function getSideNavHTML(): string {
        return "<div class='sidenav'>
                    <div class='joueurs'>
                        <ul>
                            <li>Quentin</li>
                            <li>VS</li>
                            <li>Dorian</li>
                        </ul>
                    </div>
                    <div class='historique-parties'>
                        <h4>Historique des parties</h4>
                        <ul>
                            <li>🏆 <b>Quentin</b>: Gagnant</li>
                            <li>🏆 <b>Quentin</b>: Gagnant</li>
                            <li>🏆 <b>Dorian</b>: Gagnant</li>
                            <li>🏆 <b>Dorian</b>: Gagnant</li>
                            <li>🏆 <b>Quentin</b>: Gagnant</li>
                        </ul>
                    </div>
                </div>";
    }

    public static function getFinHTML(): string {
        return "</body>
                </html>";
    }

    public static function getPageErreur($message, $urllien): string {
        return self::getDebutHTML("Erreur") . "
                <h1>Erreur</h1>
                <p>$message</p>
                <a href='$urllien'>Retour</a>
                " . self::getFinHTML();
    }
}

?>