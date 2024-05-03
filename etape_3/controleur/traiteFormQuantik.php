<?php

namespace Quantik2024;
// Déclaration des variables de session

// Chargement des classes nécessaires
require_once '../modele/PieceQuantik.php';
require_once '../modele/ArrayPieceQuantik.php';
require_once '../modele/QuantikGame.php';
//require_once 'AbstractUIGenerator.php';
require_once 'ActionQuantik.php';
// require_once 'QuantikUIGenerator.php';

session_start();

class TraiteFormQuantik
{
    const ACTION_SELECTION_PIECE = 'selectionPiece';
    const ACTION_POSE_PIECE = 'posePiece';
    const ACTION_ANNULER_CHOIX_PIECE = 'annulerChoixPiece';
    const ACTION_RECOMMENCER_PARTIE = 'recommencerPartie';

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
            case self::ACTION_RECOMMENCER_PARTIE:
                $this->traiterRecommencerPartie();
                break;
            default:
                $this->erreur('Action non reconnue');
        }
    }

    private function traiterRecommencerPartie(): void
    {
        unset($_SESSION['partie']);
        header('Location: ../vue/ChooseWhite.php');
    }

    private function traiterSelectionPiece(): void
    {
        echo "traiterSelectionPiece";
        // Récupération de la pièce sélectionnée
        $pieceSelectionnee = $_POST['selectedPiece'] ?? null;
        $partie = $_SESSION['partie'];

        // Validation de la pièce sélectionnée
        if (!isset($pieceSelectionnee)) {
            $this->erreur('Pièce non sélectionnée');
            return;
        }

        // Stockage de la pièce sélectionnée en session
        $_SESSION['pieceSelectionnee'] = $pieceSelectionnee;

        // Redirection vers la page de pose de pièce
        if($partie->currentPlayer == AbstractGame::WHITE){
            header('Location: ../vue/PoseWhite.php');
        }else{
            header('Location: ../vue/PoseBlack.php');
        }
        
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

        if($partie->currentPlayer == AbstractGame::WHITE){
            $pieceActuel = $partie->getWhitePieces()->getPieceQuantik($pieceSelectionnee);
        }else{
            $pieceActuel = $partie->getBlackPieces()->getPieceQuantik($pieceSelectionnee);
        }

        // Validation de la pose
        $actionQuantik = new ActionQuantik($partie->getPlateau());
        echo "La pose est ".$actionQuantik->isValidePose($ligne, $colonne, $pieceActuel);
        if (!$actionQuantik->isValidePose($ligne, $colonne, $pieceActuel)) {
            $this->erreur('Pose invalide');
            return;
        }

        // Pose de la pièce sur le plateau
        $actionQuantik->posePiece($ligne, $colonne, $pieceActuel);
		if($partie->currentPlayer == AbstractGame::WHITE){
            $partie->getWhitePieces()->removePieceQuantik($pieceSelectionnee);
        }else{
            $partie->getBlackPieces()->removePieceQuantik($pieceSelectionnee);
        }

        // Vérification de la victoire
        if ($actionQuantik->isRowWin($ligne) || 
            $actionQuantik->isColWin($colonne) || 
            $actionQuantik->isCornerWin($partie->getPlateau()->getCornerFromCoord($ligne,$colonne))) {

            $partie->endGame();

            // Redirection vers la page de victoire
            header('Location: ../vue/EndGame.php');
        } else {
            // Changement de joueur
            $partie->changePlayer();

            if($partie->currentPlayer == AbstractGame::WHITE){
                header('Location: ../vue/ChooseWhite.php');
            }else{
                header('Location: ../vue/ChooseBlack.php');
            }

        }
    }

    private function traiterAnnulationChoixPiece(): void
    {
        // Annulation de la sélection de pièce
        unset($_SESSION['pieceSelectionnee']);

        if($_SESSION['partie']->currentPlayer == AbstractGame::WHITE){
            header('Location: ../vue/ChooseWhite.php');
        }else{
            header('Location: ../vue/ChooseBlack.php');
        }
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
