<?php
namespace Quantik2024;

class Player
{
    public string $name;
    public int $id;
	public int $elo;

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
	public function getElo(): int
    {
        return $this->elo;
    }
    public function setElo(int $elo): void
    {
        $this->elo = $elo;
    }
    public function __toString(): string
    {
        return '('.$this->id.')'.$this->name;
    }
    public function getJson():string {
        return '{"name":"'.$this->name.'","id":'.$this->id.'}';
    }
}