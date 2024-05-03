<?php

namespace Quantik2024;
// Déclaration des variables de session

// Chargement des classes nécessaires
require_once 'PieceQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'QuantikGame.php';
require_once 'AbstractUIGenerator.php';
require_once 'ActionQuantik.php';
// require_once 'QuantikUIGenerator.php';

session_start();

class TraiteFormQuantik
{
    const ACTION_SELECTION_PIECE = 'selectionPiece';
    const ACTION_POSE_PIECE = 'posePiece';
    const ACTION_ANNULER_CHOIX_PIECE = 'annulerChoixPiece';

    public function __construct()
    {
        // Vérification de la présence d'une action dans la requête
        if (!isset($_POST['action'])) {
            $this->erreur('Action non spécifiée');
            return;
        }

        // Récupération de l'action
        $action = $_POST['action'];

        // Exécution de l'action appropriée
        switch ($action) {
            case self::ACTION_SELECTION_PIECE:
                $this->traiterSelectionPiece();
                break;
            case self::ACTION_POSE_PIECE:
                $this->traiterPosePiece();
                break;
            case self::ACTION_ANNULER_CHOIX_PIECE:
                $this->traiterAnnulationChoixPiece();
                break;
            default:
                $this->erreur('Action non reconnue');
        }
    }

    private function traiterSelectionPiece(): void
    {
        echo "traiterSelectionPiece";
        // Récupération de la pièce sélectionnée
        $pieceSelectionnee = $_POST['selectedPiece'] ?? null;
        echo $pieceSelectionnee;

        // Validation de la pièce sélectionnée
        if (!isset($pieceSelectionnee)) {
            $this->erreur('Pièce non sélectionnée');
            return;
        }

        // Stockage de la pièce sélectionnée en session
        $_SESSION['pieceSelectionnee'] = $pieceSelectionnee;

        //$v = $_SESSION['pieceSelectionnee'];

        // Redirection vers la page de pose de pièce
        header('Location: testQuantikUIGenerator.php');
    }

    private function traiterPosePiece(): void
    {
        echo "traiterPosePiece";
        // Récupération des coordonnées de la position choisie
        $ligneCol = $_POST['ligneCol'] ?? null;
        $ligne = explode(":", $ligneCol)[0];
        $colonne = explode(":", $ligneCol)[1];
        echo "La ligne".$ligne." et la colonne".$colonne;

        // Validation des coordonnées
        if (!isset($ligne) || !is_numeric($ligne) || !isset($colonne) || !is_numeric($colonne)) {
            $this->erreur('Coordonnées invalides');
            return;
        }

        // Récupération de la partie en cours et de la pièce sélectionnée
        $partie = $_SESSION['partie'];
        $pieceSelectionnee = $_SESSION['pieceSelectionnee'];
        $pieceActuel = $partie->getWhitePieces()->getPieceQuantik($pieceSelectionnee);

        // Validation de la pose
        $actionQuantik = new ActionQuantik($partie->getPlateau());
        echo "La pose est ".$actionQuantik->isValidePose($ligne, $colonne, $pieceActuel);
        if (!$actionQuantik->isValidePose($ligne, $colonne, $pieceActuel)) {
            $this->erreur('Pose invalide');
            return;
        }

        // Pose de la pièce sur le plateau
        $actionQuantik->posePiece($ligne, $colonne, $pieceActuel);

        // Vérification de la victoire
        if ($actionQuantik->isRowWin($ligne) || 
            $actionQuantik->isColWin($colonne) || 
            $actionQuantik->isCornerWin($partie->getPlateau()->getCornerFromCoord($ligne,$colonne))) {
            // Stockage du vainqueur en session
            $_SESSION['vainqueur'] = $partie->currentPlayer;

            // Redirection vers la page de victoire
            header('Location: victoire.php');
        } else {
            // Changement de joueur
            $_SESSION['couleur'] = $partie->changePlayer();
            echo $partie->getPlateau();

            // Redirection vers la page de sélection de pièce
            header('Location: testQuantikUIGenerator.php');
        }
    }

    private function traiterAnnulationChoixPiece(): void
    {
        echo "traiterAnnulationChoixPiece";
        // Annulation de la sélection de pièce
        unset($_SESSION['pieceSelectionnee']);

        // Redirection vers la page de sélection de pièce
        header('Location: testQuantikUIGenerator.php');
    }

    private function erreur(string $message): void
    {
        // Affichage d'un message d'erreur
        echo "<p style='color: red'>$message</p>";

        // Redirection vers la page de sélection de pièce
        //header('Location: testQuantikUIGenerator.php');
    }
}

// Construction de l'objet et traitement du formulaire
new TraiteFormQuantik();

?>
