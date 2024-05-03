<?php

// Utilisation d'un namespace pour éviter les conflits de noms et organiser le code.
namespace Quantik2024;

// Inclusion de la classe PieceQuantik, nécessaire pour les méthodes statiques d'initialisation.
require_once 'PieceQuantik.php';

// La classe ArrayPieceQuantik implémente les interfaces ArrayAccess et Countable.
class ArrayPieceQuantik implements \ArrayAccess, \Countable {
    // Propriété protégée pour stocker les pièces Quantik.
    protected $piecesQuantiks = [];

    // Méthode magique pour obtenir une représentation textuelle de l'ensemble des pièces.
    public function __toString(): string {
        $result = "";
        foreach ($this->piecesQuantiks as $pos => $piece) {
            $result .= "$piece";
        }
        return $result;
    }

    // Méthode pour obtenir une pièce à une position donnée.
    public function getPieceQuantik($pos): PieceQuantik {
        return $this->piecesQuantiks[$pos];
    }

    // Méthode pour définir une pièce à une position donnée.
    public function setPieceQuantik($pos, $piece): void {
        $this->piecesQuantiks[$pos] = $piece;
    }

    // Méthode pour ajouter une pièce à la fin de l'ensemble.
    public function addPieceQuantik($piece): void {
        $this->piecesQuantiks[] = $piece;
    }

    // Méthode pour supprimer une pièce à une position donnée.
    public function removePieceQuantik($pos): void {
        unset($this->piecesQuantiks[$pos]);
        // Réindexation du tableau après la suppression pour éviter les trous dans les indices.
        $this->piecesQuantiks = array_values($this->piecesQuantiks);
    }

    // Méthodes statiques d'initialisation pour créer des ensembles prédéfinis de pièces noires et blanches.
    public static function initPiecesNoires(): ArrayPieceQuantik {
        $arrayPieceQuantik = new self();
        for ($i = 0; $i < 2; $i++) {
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());
        }
        return $arrayPieceQuantik;
    }

    public static function initPiecesBlanches(): ArrayPieceQuantik {
        $arrayPieceQuantik = new self();
        for ($i = 0; $i < 2; $i++) {
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());
        }
        return $arrayPieceQuantik;
    }

    // Implémentation des méthodes nécessaires pour les interfaces ArrayAccess et Countable.

    public function offsetExists($offset): bool {
        return isset($this->piecesQuantiks[$offset]);
    }

    public function offsetGet($offset): PieceQuantik {
        return $this->piecesQuantiks[$offset];
    }

    public function offsetSet($offset, $value): void {
        $this->piecesQuantiks[$offset] = $value;
    }

    public function offsetUnset($offset): void {
        unset($this->piecesQuantiks[$offset]);
        // Réindexation du tableau après la suppression pour éviter les trous dans les indices.
        $this->piecesQuantiks = array_values($this->piecesQuantiks);
    }

    public function count(): int {
        return count($this->piecesQuantiks);
    }

    public function getJson(): string
    {
        $json = "[";
        $jTab = [];
        foreach ($this->piecesQuantiks as $p)
            $jTab[] = $p->getJson();
        $json .= implode(',', $jTab);
        return $json . ']';
    }

    public static function initArrayPieceQuantik(string|array $json): ArrayPieceQuantik
    {
        $apq = new ArrayPieceQuantik();
        if (is_string($json)) {
            $json = json_decode($json);
        }
		
        foreach ($json as $j)
            $apq->addPieceQuantik(PieceQuantik::initPieceQuantik($j));
        return $apq;
    }
}
?>
