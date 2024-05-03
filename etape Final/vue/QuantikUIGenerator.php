<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/ArrayPieceQuantik.php';
require_once '../modele/QuantikGame.php';
require_once 'AbstractUIGenerator.php';
require_once '../controleur/ActionQuantik.php';
require_once '../controleur/PDOQuantik.php';
require_once '../env/db.php';

session_start();

class QuantikUIGenerator extends AbstractUIGenerator 
{
    public static function getButtonClass($piece): string {
        $className = "";
        if ($piece->getCouleur() === PieceQuantik::WHITE) {
            $className .= "white-piece ";
        } elseif ($piece->getCouleur() === PieceQuantik::BLACK) {
            $className .= "black-piece ";
        }
        switch ($piece->getForme()) {
            case PieceQuantik::CUBE:
                $className .= "cube";
                break;
            case PieceQuantik::CONE:
                $className .= "cone";
                break;
            case PieceQuantik::CYLINDRE:
                $className .= "cylindre";
                break;
            case PieceQuantik::SPHERE:
                $className .= "sphere";
                break;
        }
        return $className;
    }

    public static function getDivPiecesDisponibles($apg): string {
        $html = "<div class='pieces-disponibles'>";
        $html .= "<table><tr>"; 
        for ($i = 0; $i < count($apg); $i++) {
            if ($i > 0 && $i % 4 == 0) {
                $html .= "</tr><tr>";
            }
            $piece = $apg->getPieceQuantik($i);
            $buttonClass = self::getButtonClass($piece);
            $html .= "<td>";
            $html .= "<button type='submit' name='selectedPiece' value='$i' class='$buttonClass'>
                        <img src='../ressources/images/pions/{$piece->getFormeAbbr()}{$piece->getCouleurAbbr()}.webp' alt='pion'>
                      </button>";
            $html .= "</td>";
        }
        $html .= "</tr></table>";
        $html .= "</div>";
        return $html;
    }
    

    public static function getFormSelectionPiece($apq): string {
        $html = "<form action='../controleur/traiteFormQuantik.php' method='post'>";
        $html .= self::getDivPiecesDisponibles($apq);
        $html .= "<input type='hidden' name='action' value='selectionPiece'>";
        $html .= "</form>";
        return $html;
    }

    public static function getDivPlateauQuantik($plateau): string {
        $html = "<table class='plateau'>";
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            $html .= "<tr>";
            for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
                $currentPiece = $plateau->getPiece($i, $j);
                $isEmpty = is_null($currentPiece);
                $pieceContent = !$isEmpty ? "({$currentPiece->getFormeAbbr()}&nbsp;{$currentPiece->getCouleurAbbr()})" : "";
                $html .= "<td>";
                $html .= "<button type='submit' name='selectedPosition' value='($i,$j)' class='pion' disabled>";
                if($pieceContent == "(&nbsp;)")
                    $html .= "<span class='point'></span>";
                else
                    $html .= "<img src='../ressources/images/pions/{$currentPiece->getFormeAbbr()}{$currentPiece->getCouleurAbbr()}.webp' alt='pion' width='70px'>";
                $html .= "</button>";
                $html .= "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";
        return $html;
    }

    public static function getDivClassement(): string {
        PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
        $allPlayers = PDOQuantik::getAllPlayers();

        $html = "<div>";
        $html .= "<h1>Classement</h1>";
        
        foreach($allPlayers as $i => $player)
        {
            $html .= "<div class='carte-historique'>";
            $html .= '<div class="joueur-victoire">';
            $html .= "<h3>".$player["name"]."</h3>";
            $html .= "</div>";
            $html .= "<p>"."Elo : ".$player["elo"]."</p>";
            $html .= "</div>";
        }

        
        $html .= "</div>";

        return $html;

    }

    public static function getFormPlateauQuantik($plateau, $piece): string {
        $plateauAction = new ActionQuantik($plateau);
        $html = "<form class='plateau' action='../controleur/traiteFormQuantik.php' method='post'>";
        $html .= "<table class='w100'>";
    
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            $html .= "<tr>";
            for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
                $currentPiece = $plateau->getPiece($i, $j);
                $isEmpty = is_null($currentPiece);
                $buttonClass = $isEmpty ? "creuse" : "sureleve";
                $disabled = !$plateauAction->isValidePose($i, $j, $piece) ? "disabled" : "";                
                $pieceContent = !$isEmpty ? "({$currentPiece->getFormeAbbr()}&nbsp;{$currentPiece->getCouleurAbbr()})" : "";
                $html .= "<td>";
                $html .= "<input type='hidden' name='action' value='posePiece'>";
                if($pieceContent == "(&nbsp;)")
                    if($disabled == "disabled")
                    {
                        $html .= "<button type='submit' name='ligneCol' value='$i:$j' class='pion $buttonClass' disabled><span class='point'></span></button>";

                    }
                    else
                    {
                        $html .= "<button type='submit' name='ligneCol' value='$i:$j' class='pion $buttonClass'>";
                        $html .= "<img src='../ressources/images/pions/F{$piece->getFormeAbbr()}{$piece->getCouleurAbbr()}.webp' alt='pion' width='70px'>";
                        $html .= "</button>";
                    }
                   
                else
                    $html .= "<img src='../ressources/images/pions/{$currentPiece->getFormeAbbr()}{$currentPiece->getCouleurAbbr()}.webp' alt='pion' width='70px'>";
                $html .= "</button>";
                $html .= "</td>";
            }
            $html .= "</tr>";
        }
    
        $html .= "</table>";
        $html .= "</form>";
        $html .= "<div class='plateaux-droit'>";
        $html .= "<div class='piece-selectionnee'>"
                . "<img src='../ressources/images/pions/{$piece->getFormeAbbr()}{$piece->getCouleurAbbr()}.webp' alt='pion'>"
                . "</div>";
        $html .= self::getFormBoutonAnnulerChoixPiece();
        $html .= "</div>";
        return $html;
    }
    

    public static function getFormBoutonAnnulerChoixPiece(): string {
        return "<form action='../controleur/traiteFormQuantik.php' method='post'>
                    <button type='submit' class='btnAnnulerRecommencer' name='cancelPieceSelection'>Annuler</button>
                    <input type='hidden' name='action' value='annulerChoixPiece'>
                </form>";
    }

    public static function getDivMessageVictoire($quantik, $couleur): string {
        $message = $couleur === PieceQuantik::WHITE ? "Blancs" : "Noirs";
		$playerWin = $quantik->couleursPlayers[$quantik->currentPlayer]->getName();
		$html = "<div class='message-victoire'>";
		$html .= " $playerWin($message) gagnent !<small>";
		if($playerWin !== $_SESSION["player"]->getName())
			$html .= "<br> Tu es mauvais ta perdu";
		else
			$html .= "<br> Bien joué, tu es un vainqueur !";
		$html .= "</small></div>";
        return $html;
    }

    public static function getLienRecommencer(): string {
        $html = "<form action='../controleur/traiteFormQuantik.php' method='post'>";
        $html .= "<input type='hidden' name='action' value='recommencerPartie'>";
        $html .= "<button type='submit' class='btnAnnulerRecommencer'>Retour au Menu</button>";
        $html .= "</form>";
        return $html;
    }

    public static function getFormCreerPartie(): string {
        $html = "<div class='nouvelle-partie'>";
        $html .= "<form action='../controleur/traiteFormQuantik.php' method='post'>";
        $html .= "<input type='hidden' name='action' value='creerPartie'>";
        $html .= "<button type='submit' class='btnCreerPartie profil'>Crée une Partie</button>";
        $html .= "</form>";
        $html .= "</div>";
        return $html;
    }

    public static function getFormPartieRecherche(): string {
        PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
        $playerName = $_SESSION["player"]->getName();
        $gameDeMoi = PDOQuantik::getAllGameQuantikInSearch();
		
        $html = "<div>";
        $html .= self::getFormCreerPartie();
        $html .= "<h1>Parties en recherche</h1>";
		$html .= "<form action='../controleur/traiteFormQuantik.php' method='post'>";

        foreach ($gameDeMoi as $i => $game){
            $html .= "<div class='carte-partie'>";
            $partie = QuantikGame::initQuantikGame($game["json"]);
            $html .= $partie;
			if($partie->couleursPlayers[0]->getName() !== $playerName)
			{
				$html .= "<input type='hidden' name='action' value='rejoindrePartie'>";
				$html .= "<button type='submit' name='idPartie' class='btnRejoindrePartie profil' value='$partie->gameId'> Rejoindre </button>";
			}
            $html .= "</div>";
        }
		
		$html .= "</form>";
        $html .= "</div>";

        return $html;
    }
	
	public static function getFormHistorique(): string {
        PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
        $playerName = $_SESSION["player"]->getName();
        $gameDeMoi = PDOQuantik::getHistoriqueByPlayer($playerName);
		
		$html = "<div>";
        $html .= "<h1>Historique</h1>";
		$html .= "<form action='../controleur/traiteFormQuantik.php' method='post'>";

        foreach ($gameDeMoi as $i => $game){
            $html .= "<div class='carte-partie'>";
            $partie = QuantikGame::initQuantikGame($game["json"]);
            $html .= $partie;
			$html .= "<input type='hidden' name='action' value='historiquePartie'>";
			$html .= "<button type='submit' name='idPartie' class='btnHistorique profil' value='$partie->gameId'> Consulter </button>";
            $html .= "</div>";
        }
		
		$html .= "</form>";
		$html .= "</div>";

        return $html;
    }
	
	public static function getFormPartieEnCours(): string {
        PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
        $playerName = $_SESSION["player"]->getName();
        $gameDeMoi = PDOQuantik::getAllGameQuantikByPlayerNameInGame($playerName);
		
        $html = "<div>";
        $html .= "<h1>Parties en cours</h1>";
		$html .= "<form action='../controleur/traiteFormQuantik.php' method='post'>";

        foreach ($gameDeMoi as $i => $game){
            $partie = QuantikGame::initQuantikGame($game["json"]);
            $html .= "<div class='carte-partie'>";
            $html .= "<div class='joueurs-parties-en-cours'>";
            $html .= $partie;
            $html .= "</div>";
			$html .= "<input type='hidden' name='action' value='jouerPartie'>";
			$indexPlayer = array_search($_SESSION["player"]->name, array_column($partie->couleursPlayers, 'name'));
			$html .= "<button type='submit' name='idPartie' class='btnJouerPartie profil' value='$partie->gameId'>".
					($partie->currentPlayer == $indexPlayer ? 'Jouer' : 'Consulter')."</button>";
            $html .= "</div>";
        }

        $html .= "</form>";
        $html .= "</div>";

        return $html;
    }


    public static function getPageSelectionPiece($quantik, $couleurActive): string {
        $html = self::getDebutHTML("Sélection de pièce");
        $html .= self::getAppbarHTML();
        $html .= self::getSideNavHTML();
        $html .= '<div class="content">';
        $html .= self::getDivPlateauQuantik($quantik->getPlateau());
        $html .= "<div class='plateaux-droit'>";
        $html .= "<div class='piece-selectionnee'></div>";
        $html .= self::getFormSelectionPiece($couleurActive === PieceQuantik::WHITE ? $quantik->getWhitePieces() : $quantik->getBlackPieces());
        $html .= '</div>';
        $html .= '</div>';
        $html .= self::getFinHTML();
        return $html;
    }

    public static function getPagePosePiece($quantik, $couleurActive, $posSelection): string {
        $html = self::getDebutHTML("Pose de pièce");
        $html .= self::getAppbarHTML();
        $html .= self::getSideNavHTML();
        $html .= '<div class="content">';
        $html .= self::getFormPlateauQuantik($quantik->getPlateau(), $couleurActive === PieceQuantik::WHITE ? $quantik->getWhitePieces()->getPieceQuantik($posSelection) : $quantik->getBlackPieces()->getPieceQuantik($posSelection));
        $html .= self::getFinHTML();
        return $html;
    }

    public static function getPageVictoire($quantik, $couleurActive): string {
        $html = self::getDebutHTML("Victoire");
        $html .= self::getAppbarHTML();
        $html .= self::getSideNavHTML();
        $html .= '<div class="victoire">';
        $html .= "<h1 class='mb0'>Partie terminée</h1>";
        $html .= self::getDivMessageVictoire($quantik, $couleurActive);
        $html .= self::getLienRecommencer();
        $html .= '</div>';
        $html .= self::getDivPlateauQuantik($quantik->getPlateau());
        $html .= self::getFinHTML();
        return $html;
    }

    public static function getPageHome(): string{
        $html = self::getDebutHTML("Home");
        $html .= self::getAppbarHTML();
        $html .= '<div class="accueil">';
        $html .= '<div class="four-columns-grid">';
		$html .= self::getFormHistorique();
		$html .= self::getDivClassement();
        $html .= self::getFormPartieRecherche();
		$html .= self::getFormPartieEnCours();
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
	
	public static function getPageConsultation($quantik): string {
        $html = self::getDebutHTML("Sélection de pièce");
        $html .= self::getAppbarHTML();
        $html .= self::getSideNavHTML();
        $html .= '<div class="content">';
        $html .= self::getDivPlateauQuantik($quantik->getPlateau());
        $html .= "<div class='plateaux-droit'>";
		$html .= '<form action="Home.php">';
        $html .=  "<button type='submit' class='profil' style=' margin-bottom: 20px' >Retour au menu</button>";
		$html .= '</form>';
        $html .= self::getDivPiecesDisponibles($quantik->getWhitePieces());
		$html .= self::getDivPiecesDisponibles($quantik->getBlackPieces());
        $html .= '</div>';
        $html .= self::getFinHTML();
        return $html;
    }

}
?>