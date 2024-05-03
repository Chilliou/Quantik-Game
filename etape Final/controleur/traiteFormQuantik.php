<?php

namespace Quantik2024;
// Déclaration des variables de session

// Chargement des classes nécessaires
require_once '../modele/PieceQuantik.php';
require_once '../modele/ArrayPieceQuantik.php';
require_once '../modele/QuantikGame.php';
require_once 'ActionQuantik.php';
require_once 'PDOQuantik.php';
require_once '../vue/AbstractUIGenerator.php';
require_once '../env/db.php';

session_start();

class TraiteFormQuantik
{
    const ACTION_SELECTION_PIECE = 'selectionPiece';
    const ACTION_POSE_PIECE = 'posePiece';
    const ACTION_ANNULER_CHOIX_PIECE = 'annulerChoixPiece';
    const ACTION_RECOMMENCER_PARTIE = 'recommencerPartie';

    const ACTION_CREER_PARTIE = 'creerPartie';
	const ACTION_REJOINDRE_PARTIE = 'rejoindrePartie';
	const ACTION_JOUER_PARTIE = 'jouerPartie';
	const ACTION_HISTORIQUE_PARTIE = 'historiquePartie';

    public function __construct()
    {
        // Vérification de la présence d'une action dans la requête
        if (!isset($_POST['action'])) {
            $this->erreur('Action non spécifiée');
            return;
        }
		
		PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

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
            case self::ACTION_CREER_PARTIE:
                $this->traiterCreerPartie();
                break;
			case self::ACTION_REJOINDRE_PARTIE:
                $this->traiterRejoindrePartie();
                break;
			case self::ACTION_JOUER_PARTIE:
                $this->traiterJouerPartie();
                break;
			case self::ACTION_HISTORIQUE_PARTIE:
                $this->traiterHistoriquePartie();
                break;
            default:
                $this->erreur('Action non reconnue');
        }
    }

    private function traiterRecommencerPartie(): void
    {
        unset($_SESSION['partie']);
        header('Location: ../vue/Home.php');
    }

    private function traiterSelectionPiece(): void
    {
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
        // Récupération des coordonnées de la position choisie
        $ligneCol = $_POST['ligneCol'] ?? null;
        $ligne = explode(":", $ligneCol)[0];
        $colonne = explode(":", $ligneCol)[1];

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
			PDOQuantik::saveGameQuantik("finished", $partie->getJson(), $partie->getGameId());
			
			$player1 = $partie->couleursPlayers[0]->getName();
			$player2 = $partie->couleursPlayers[1]->getName();
			
			if($partie->currentPlayer == AbstractGame::WHITE){
				$this->updateElo($player1, $player2, 1, 0);
			}else{
				$this->updateElo($player1, $player2, 0, 1);
			}
			
			PDOQuantik::saveGameQuantik("finished", $partie->getJson(), $partie->getGameId());

            // Redirection vers la page de victoire
            header('Location: ../vue/EndGame.php');
        } else {
            // Changement de joueur
            $partie->changePlayer();
			
			PDOQuantik::saveGameQuantik("initialized", $partie->getJson(), $partie->getGameId());
			

            header('Location: ../vue/Consultation.php');

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

    private function traiterCreerPartie(): void
    {
        $plateau = new PlateauQuantik();
        $blackPieces = ArrayPieceQuantik::initPiecesNoires();
        $whitePieces = ArrayPieceQuantik::initPiecesBlanches();
        $couleurPlayers = array(PieceQuantik::WHITE => $_SESSION["player"]);

        $partie = new QuantikGame($plateau, $blackPieces, $whitePieces, $couleurPlayers);
		
        PDOQuantik::createGameQuantik($_SESSION["player"]->getName(), $partie->getJson());
		$partie->setGameId(PDOQuantik::getLastGameIdForPlayer($_SESSION["player"]->getName()));
		PDOQuantik::saveGameQuantik("waitingForPlayer", $partie->getJson(), $partie->getGameId());

        header('Location: ../vue/Home.php');

    }
	
	private function traiterRejoindrePartie(): void
    {		
		$gameId = $_POST['idPartie'] ?? null;
		if (!isset($gameId) || !is_numeric($gameId) ) {
            $this->erreur('ID de la partie incorrect');
            return;
        }
		$partiePDO = PDOQuantik::getGameQuantikById($gameId);
		foreach ($partiePDO as $i => $game){
			$partie = QuantikGame::initQuantikGame($game["json"]);
		}
		
		$partie->couleursPlayers[PieceQuantik::BLACK] = $_SESSION["player"];
		
        PDOQuantik::addPlayerToGameQuantik($_SESSION["player"]->getName(), $partie->getJson(),$gameId);

        header('Location: ../vue/Home.php');

    }
	
	public function traiterJouerPartie():void
	{
		$gameId = $_POST['idPartie'] ?? null;
		if (!isset($gameId) || !is_numeric($gameId) ) {
            $this->erreur('ID de la partie incorrect');
            return;
        }
		$partiePDO = PDOQuantik::getGameQuantikById($gameId);
		foreach ($partiePDO as $i => $game){
			$partie = QuantikGame::initQuantikGame($game["json"]);
		}
		
		$_SESSION['partie'] = $partie;
		$indexPlayer = array_search($_SESSION["player"]->name, array_column($partie->couleursPlayers, 'name'));
		
		if($partie->currentPlayer == $indexPlayer)
		{
			if($partie->currentPlayer == AbstractGame::WHITE){
                header('Location: ../vue/ChooseWhite.php');
            }else{
                header('Location: ../vue/ChooseBlack.php');
            }
				
		}else{
			header('Location: ../vue/Consultation.php');
		}
		
	}

    private function erreur(string $message): void
    {
        // Affichage d'un message d'erreur
		echo AbstractUIGenerator::getPageErreur($message,"../vue/Home.php");
    }
	
	private function updateElo($player1, $player2 , $score1, $score2): void 
	{
		$K = 32;  // Facteur de K (à ajuster en fonction du jeu)
			
		$eloPlayer1 = PDOQuantik::selectPlayerElo($player1);
		$eloPlayer2 = PDOQuantik::selectPlayerElo($player2);

		// Calcul de l'écart d'Elo
		$ecart_elo = $eloPlayer1 - $eloPlayer2;

		// Calcul de l'espérance de victoire du joueur 1
		$proba_victoire_joueur1 = 1 / (1 + 10 ** ($ecart_elo / 400));

		// Gain/perte de points Elo
		$gain_joueur1 = $K * ($score1 - $proba_victoire_joueur1);
		$gain_joueur2 = $K * ($score2 - (1 - $proba_victoire_joueur1));

		// Mise à jour des classements
		(int) $nouveauEloPlayer1 = $eloPlayer1 + $gain_joueur1;
		(int) $nouveauEloPlayer2 = $eloPlayer2 + $gain_joueur2;
		
		PDOQuantik::updateEloPlayer($player1, (int) $nouveauEloPlayer1);
		PDOQuantik::updateEloPlayer($player2, (int) $nouveauEloPlayer2);
	}
	
	private function traiterHistoriquePartie(): void
	{
		$gameId = $_POST['idPartie'] ?? null;
		if (!isset($gameId) || !is_numeric($gameId) ) {
            $this->erreur('ID de la partie incorrect');
            return;
        }
		$partiePDO = PDOQuantik::getGameQuantikById($gameId);
		foreach ($partiePDO as $i => $game){
			$partie = QuantikGame::initQuantikGame($game["json"]);
		}
		
		$_SESSION['partie'] = $partie;
		
		header('Location: ../vue/EndGame.php');
	}
}

// Construction de l'objet et traitement du formulaire
new TraiteFormQuantik();

?>
