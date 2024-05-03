<?php

namespace Quantik2024;

// La classe PieceQuantik représente une pièce du jeu Quantik avec une forme et une couleur.
class PieceQuantik {
    // Définition des constantes pour les couleurs (blanc et noir) et les formes (vide, cube, cône, cylindre, sphère).
    const WHITE = 0;
    const BLACK = 1;
    const VOID = 0;
    const CUBE = 1;
    const CONE = 2;
    const CYLINDRE = 3;
    const SPHERE = 4;

    // Propriétés protégées pour stocker la forme et la couleur de la pièce.
    protected $forme;
    protected $couleur;

    // Constructeur privé pour empêcher l'instanciation directe et encourager l'utilisation des méthodes d'initialisation statiques.
    private function __construct($forme, $couleur) {
        $this->forme = $forme;
        $this->couleur = $couleur;
    }

    // Méthodes d'accès pour obtenir la forme et la couleur de la pièce.
    public function getForme(): int {
        return $this->forme;
    }

    public function getCouleur(): int {
        return $this->couleur;
    }

    // Méthode magique pour obtenir une représentation textuelle de la pièce.
    public function __toString(): string {
        // Utilisation d'un switch pour déterminer la représentation textuelle de la forme.
        switch ($this->forme) {
            case 1:
                $sForme = 'Cu';
                break;
            case 2:
                $sForme = 'Co';
                break;
            case 3:
                $sForme = 'Cy';
                break;
            case 4:
                $sForme = 'Sp';
                break;
        }

        // Utilisation d'un ternaire pour déterminer la couleur en fonction de la constante.
        $sCouleur = ($this->couleur === self::WHITE) ? 'W' : 'B';

        // Vérification spéciale pour la pièce vide.
        if ($this->couleur === self::VOID && $this->forme === self::VOID) {
            return '(&nbsp;&nbsp;&nbsp;&nbsp;)';
        }

        // Retourne la représentation textuelle de la pièce.
        return '(' . $sForme . ':' . $sCouleur . ')';
    }

    // Méthodes statiques d'initialisation pour créer des instances prédéfinies de pièces.
    public static function initVoid(): PieceQuantik {
        return new self(self::VOID, self::VOID);
    }

    public static function initWhiteCube(): PieceQuantik {
        return new self(self::CUBE, self::WHITE);
    }

    public static function initBlackCube(): PieceQuantik {
        return new self(self::CUBE, self::BLACK);
    }

    public static function initWhiteCone(): PieceQuantik {
        return new self(self::CONE, self::WHITE);
    }

    public static function initBlackCone(): PieceQuantik {
        return new self(self::CONE, self::BLACK);
    }

    public static function initWhiteCylindre(): PieceQuantik {
        return new self(self::CYLINDRE, self::WHITE);
    }

    public static function initBlackCylindre(): PieceQuantik {
        return new self(self::CYLINDRE, self::BLACK);
    }

    public static function initWhiteSphere(): PieceQuantik {
        return new self(self::SPHERE, self::WHITE);
    }

    public static function initBlackSphere(): PieceQuantik {
        return new self(self::SPHERE, self::BLACK);
    }

    public function getFormeAbbr(): string {
        switch ($this->forme) {
            case self::CUBE:
                return "Cu";
            case self::CONE:
                return "Co";
            case self::CYLINDRE:
                return "Cy";
            case self::SPHERE:
                return "Sp";
            default:
                return "";
        }
    }

    public function getCouleurAbbr(): string {
        if($this->forme == self::VOID)
            return "";
        return $this->couleur === self::WHITE ? "W" : "B";
    }
}



?>
