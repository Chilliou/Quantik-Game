<?php

namespace Quantik2024;

// Importe la classe ArrayPieceQuantik nécessaire
require_once 'ArrayPieceQuantik.php';

class PlateauQuantik {
    const NBROWS = 4;
    const NBCOLS = 4;

    const NW = 0;
    const NE = 1;
    const SW = 2;
    const SE = 3;

    protected $cases;

    // Constructeur initialisant un plateau avec des pièces vides.
    public function __construct() {
        $this->cases = new ArrayPieceQuantik;

        // Initialisation du plateau avec des pièces vides.
        for ($i = 0; $i < self::NBROWS * self::NBCOLS; $i++) {
            $this->cases->addPieceQuantik(PieceQuantik::initVoid());
        }
    }

    // Obtient une pièce du plateau en fonction des coordonnées.
    public function getPiece($rowNum, $colNum): PieceQuantik {
        return $this->cases->getPieceQuantik($colNum + $rowNum * self::NBCOLS);
    }

    // Définit une pièce sur le plateau en fonction des coordonnées.
    public function setPiece($rowNum, $colNum, $p): void {
        $this->cases->setPieceQuantik($colNum + $rowNum * self::NBCOLS, $p);
    }

    // Obtient une rangée du plateau en fonction du numéro de rangée.
    public function getRow($numRow): ArrayPieceQuantik {
        $rowArray = new ArrayPieceQuantik();

        for ($i = 0; $i < self::NBCOLS; $i++) {
            $rowArray->addPieceQuantik($this->getPiece($numRow, $i));
        }

        return $rowArray;
    }

    // Obtient une colonne du plateau en fonction du numéro de colonne.
    public function getCol($numCol): ArrayPieceQuantik {
        $colArray = new ArrayPieceQuantik();

        for ($i = 0; $i < self::NBROWS; $i++) {
            $colArray->addPieceQuantik($this->getPiece($i, $numCol));
        }

        return $colArray;
    }

    // Obtient un coin du plateau en fonction de la direction (NW, NE, SW, SE).
    public function getCorner($dir): ArrayPieceQuantik {
        $cornerArray = new ArrayPieceQuantik();

        // Définition des indices de ligne et de colonne pour chaque coin.
        $cornerIndices = [
            self::NW => [[0, 0], [0, 1], [1, 0], [1, 1]],
            self::NE => [[0, 2], [0, 3], [1, 2], [1, 3]],
            self::SW => [[2, 0], [2, 1], [3, 0], [3, 1]],
            self::SE => [[2, 2], [2, 3], [3, 2], [3, 3]],
        ];

        // Vérifie si la direction spécifiée existe dans le tableau des indices de coin.
        if (array_key_exists($dir, $cornerIndices)) {
            // Parcours des indices de ligne et de colonne pour le coin spécifié.
            foreach ($cornerIndices[$dir] as [$row, $col]) {
                // Ajoute la pièce correspondante à l'ensemble des coins.
                $cornerArray->addPieceQuantik($this->getPiece($row, $col));
            }
        }

        return $cornerArray;
    }

    public function __toString(): string {
        $result = "";
        for ($i = 0; $i < self::NBROWS; $i++) {
            for ($y = 0; $y < self::NBCOLS; $y++) {
                $result .= $this->getPiece($i, $y);
            }
            $result .= "<br \>";
        }

        return $result;
    }

    // Obtient la direction du coin en fonction des coordonnées.
    public static function getCornerFromCoord($rowNum, $colNum): int {
        return match ($colNum + $rowNum * self::NBCOLS) {
            0, 1, 4, 5 => self::NW,
            2, 3, 6, 7 => self::NE,
            8, 9, 12, 13 => self::SW,
            10, 11, 14, 15 => self::SE,
            default => throw new InvalidArgumentException("Out Position"),
        };
    }

    public function getJson(): string
    {
        return $this->cases->getJson();
    }


    public static function initPlateauQuantik($json): PlateauQuantik
{
  $pq = new PlateauQuantik();

  if (is_string($json))
    $json = json_decode($json);

  $apqFromJson = ArrayPieceQuantik::initArrayPieceQuantik($json);

  if ($apqFromJson->count() != 0) {
    $pq->cases = $apqFromJson;
  }

  return $pq;
}


}
?>
