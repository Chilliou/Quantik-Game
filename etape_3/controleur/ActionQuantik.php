<?php

namespace Quantik2024;

// Importe la classe PlateauQuantik nécessaire
require_once '../modele/PlateauQuantik.php';

class ActionQuantik {
    protected $plateau;

    // Constructeur prenant un plateau comme argument
    public function __construct($plateau) {
        $this->plateau = $plateau;
    }

    // Obtient le plateau associé à l'action
    public function getPlateau(): PlateauQuantik {
        return $this->plateau;
    }

    // Vérifie si une rangée est gagnante
    public function isRowWin($numRow): bool {
        $row = $this->plateau->getRow($numRow);
        return $this->isComboWin($row);
    }

    // Vérifie si une colonne est gagnante
    public function isColWin($numCol): bool {
        $col = $this->plateau->getCol($numCol);
        return $this->isComboWin($col);
    }

    // Vérifie si un coin est gagnant
    public function isCornerWin($dir): bool {
        $corner = $this->plateau->getCorner($dir);
        return $this->isComboWin($corner);
    }

    // Vérifie si la pose d'une pièce à une position spécifique est valide
    public function isValidePose($rowNum, $colNum, $piece): bool {
        $row = $this->plateau->getRow($rowNum);
        $col = $this->plateau->getCol($colNum);
        $cornerDir = PlateauQuantik::getCornerFromCoord($rowNum, $colNum);
        $corner = $this->plateau->getCorner($cornerDir);

        return $this->plateau->getPiece($rowNum, $colNum) == PieceQuantik::initVoid() &&
               $this->isPieceValide($row, $piece) &&
               $this->isPieceValide($col, $piece) &&
               $this->isPieceValide($corner, $piece);
    }

    // Pose une pièce à une position spécifique si la pose est valide
    public function posePiece($rowNum, $colNum, $piece): void {


        if ($this->isValidePose($rowNum, $colNum, $piece)) {
            $this->plateau->setPiece($rowNum, $colNum, $piece);
        }
    }

    // Affiche le plateau en tant que chaîne de caractères
    public function __toString(): string {
        return $this->plateau;
    }

    // Vérifie si une combinaison de pièces est gagnante
    private static function isComboWin($pieces): bool {
        $ret = true;
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            if ($ret == true) {
                $ret = $pieces->getPieceQuantik($i)->getForme() != 0;
            }

            for ($y = 0; $y < PlateauQuantik::NBCOLS; $y++) {
                if ($i != $y && $ret == true) {
                    $ret = $pieces->getPieceQuantik($i)->getForme() != $pieces->getPieceQuantik($y)->getForme();
                }
            }
        }
        return $ret;
    }

    // Vérifie si une pièce est valide dans un ensemble de pièces
    private static function isPieceValide($pieces, $p): bool {
        for ($i = 0; $i < $pieces->count(); $i++) {
            if ($pieces->getPieceQuantik($i)->getForme() === $p->getForme() &&
                $pieces->getPieceQuantik($i)->getCouleur() != $p->getCouleur()) {
                return false;
            }
        }

        return true;
    }
}
?>
