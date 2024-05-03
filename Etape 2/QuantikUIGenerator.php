<?php

namespace Quantik2024;

require_once 'PieceQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'QuantikGame.php';
require_once 'AbstractUIGenerator.php';
require_once 'ActionQuantik.php';

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
        $html = "";

        for($i = 0; $i < count($apg); $i++){
            $piece = $apg[$i];

            $buttonText = "({$piece->getFormeAbbr()}:{$piece->getCouleurAbbr()})";
            $buttonClass = self::getButtonClass($piece);
            $html .= "<button type='submit' name='selectedPiece' value='$i' class='$buttonClass'>$buttonText</button> ";
        }

        return $html;
    }

    public static function getFormSelectionPiece($apq): string {

        //$getDivPiecesDispo = self::getDivPiecesDisponibles($apq);

        $html = "<form action='traiteFormQuantik.php' method='post'>";
        $html .= self::getDivPiecesDisponibles($apq);
        $html .= "<input type='hidden' name='action' value='selectionPiece'>";
        $html .= "</form>";
        return $html;
    }

    // génère un formulaire proposant de choisir une pièce parmi celle du tableau fourni en entrée. Le formulaire transmettra la position de la pièce dans le tableau. L'action du formulaire envoi sur traiteFormQuantik.php ;

    public static function getDivPlateauQuantik($plateau): string {
        $html = "<div class='plateau'>";
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
                $piece = $plateau->getPiece($i, $j);
                $buttonClass = self::getButtonClass($piece);
                $html .= "<button type='submit' name='selectedPosition' value='($i,$j)' class='$buttonClass' disabled>
                            ({$piece->getFormeAbbr()}:{$piece->getCouleurAbbr()})
                          </button>";
            }
            $html .= "<br>";
        }
        $html .= "</div>";
        return $html;
    }

    public static function getFormPlateauQuantik($plateau, $piece): string {
        $plateauAction = new ActionQuantik($plateau);
        $html = "<form action='traiteFormQuantik.php' method='post'>";
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
                $buttonClass = "playable";
                if (!$plateauAction->isValidePose($i, $j, $piece)) {
                    $buttonClass .= " disabled";
                }
                $html .= "<input type='hidden' name='action' value='posePiece'>";
                $html .= "<button type='submit' name='ligneCol' value='$i:$j' $buttonClass>
                            ({$plateau->getPiece($i,$j)->getFormeAbbr()}&nbsp&nbsp&nbsp&nbsp{$plateau->getPiece($i,$j)->getCouleurAbbr()})
                          </button>";
            }
            $html .= "<br>";
        }
        $html .= "</form>";
        return $html;
    }

    public static function getFormBoutonAnnulerChoixPiece(): string {
        return "<form action='traiteFormQuantik.php' method='post'>
                    <button type='submit' name='cancelPieceSelection'>Annuler</button>
                    <input type='hidden' name='action' value='annulerChoixPiece'>
                </form>";
    }

    public static function getDivMessageVictoire($couleur): string {
        $message = $couleur === PieceQuantik::WHITE ? "Blancs" : "Noirs";
        return "<div class='message-victoire'>$message gagnent !</div>";
    }

    public static function getLienRecommencer(): string {
        return "<a href='traiteFormQuantik.php?recommencer=true'>Recommencer</a>";
    }

    public static function getPageSelectionPiece($quantik, $couleurActive): string {
        $html = self::getDebutHTML("Sélection de pièce");
        $html .= "<h1>Sélection de pièce</h1>";
        $html .= self::getFormSelectionPiece($couleurActive === PieceQuantik::WHITE ? $quantik->getWhitePieces() : $quantik->getBlackPieces());
        $html .= self::getFinHTML();
        return $html;
    }

    public static function getPagePosePiece($quantik, $couleurActive,$posSelection): string {
        $html = self::getDebutHTML("Pose de pièce");
        $html .= "<h1>Pose de pièce</h1>";
        $html .= "<p>Choisissez une position pour la pièce sélectionnée :</p>";
        $html .= self::getFormPlateauQuantik($quantik->getPlateau(), $couleurActive === PieceQuantik::WHITE ? $quantik->getWhitePieces()->getPieceQuantik($posSelection) : $quantik->getBlackPieces()->getPieceQuantik($posSelection));
        $html .= self::getFormBoutonAnnulerChoixPiece();
        $html .= self::getFinHTML();
        return $html;
    }

    public static function getPageVictoire($quantik, $couleurActive): string {
        $html = self::getDebutHTML("Victoire");
        $html .= "<h1>Partie terminée</h1>";
        $html .= self::getDivMessageVictoire($couleurActive);
        $html .= self::getLienRecommencer();
        $html .= self::getFinHTML();
        return $html;
    }

}
?>