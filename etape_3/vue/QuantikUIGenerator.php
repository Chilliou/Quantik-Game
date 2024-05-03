<?php

namespace Quantik2024;

require_once '../modele/PieceQuantik.php';
require_once '../modele/ArrayPieceQuantik.php';
require_once '../modele/QuantikGame.php';
require_once 'AbstractUIGenerator.php';
require_once '../controleur/ActionQuantik.php';

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

        //$getDivPiecesDispo = self::getDivPiecesDisponibles($apq);

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

    // public static function getFormPlateauQuantik($plateau, $piece): string {
    //     $plateauAction = new ActionQuantik($plateau);
    //     $html = "<form action='traiteFormQuantik.php' method='post'>";
    //     echo "La piece est ".$piece;
    //     for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
    //         for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
    //             $buttonClass = "playable";
    //             if (!$plateauAction->isValidePose($i, $j, $piece)) {
    //                 $buttonClass .= " disabled";
    //             }
    //             $html .= "<input type='hidden' name='action' value='posePiece'>";
    //             $html .= "<button type='submit' name='ligneCol' value='$i:$j' $buttonClass>
    //                         ({$plateau->getPiece($i,$j)->getFormeAbbr()}&nbsp&nbsp&nbsp&nbsp{$plateau->getPiece($i,$j)->getCouleurAbbr()})
    //                       </button>";
    //         }
    //         $html .= "<br>";
    //     }
    //     $html .= "</form>";
    //     return $html;
    // }

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

    public static function getDivMessageVictoire($couleur): string {
        $message = $couleur === PieceQuantik::WHITE ? "Blancs" : "Noirs";
        return "<div class='message-victoire'>$message gagnent !</div>";
    }

    public static function getLienRecommencer(): string {
        $html = "<form action='../controleur/traiteFormQuantik.php' method='post'>";
        $html .= "<input type='hidden' name='action' value='recommencerPartie'>";
        $html .= "<button type='submit' class='btnAnnulerRecommencer'>Recommencer</button>";
        $html .= "</form>";
        return $html;
    }

    public static function getPageSelectionPiece($quantik, $couleurActive): string {
        $html = self::getDebutHTML("Sélection de pièce");
        $html .= self::getAppbarHTML();
        $html .= self::getSideNavHTML();
        $html .= '<div class="content">';
        //$html .= "<h1>Sélection de pièce</h1>";
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
        //$html .= "<h1>Pose de pièce</h1>";
        //$html .= "<p>Choisissez une position pour la pièce sélectionnée :</p>";
        $html .= self::getFinHTML();
        return $html;
    }

    public static function getPageVictoire($quantik, $couleurActive): string {
        $html = self::getDebutHTML("Victoire");
        $html .= self::getAppbarHTML();
        $html .= self::getSideNavHTML();
        $html .= '<div class="victoire">';
        $html .= "<h1 class='mb0'>Partie terminée</h1>";
        $html .= self::getDivMessageVictoire($couleurActive);
        $html .= self::getLienRecommencer();
        $html .= '</div>';
        $html .= self::getDivPlateauQuantik($quantik->getPlateau());
        $html .= self::getFinHTML();
        return $html;
    }

}
?>