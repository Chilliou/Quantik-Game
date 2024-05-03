<?php

namespace Quantik2024;


class AbstractUIGenerator {
    public static function getDebutHTML($title = "title content"): string {
        return "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>$title</title>
                </head>
                <body>";
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