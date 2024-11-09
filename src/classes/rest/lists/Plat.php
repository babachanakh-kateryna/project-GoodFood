<?php

namespace iutnc\deefy\rest\lists;

/**
 * Class Plat est une classe qui represente un plat
 */
class Plat
{
    protected int $numplat;
    protected string $libelle;
    protected string $type;
    protected float $prixunit;

    public function __construct(int $numplat, string $libelle, string $type, float $prixunit)
    {
        $this->numplat = $numplat;
        $this->libelle = $libelle;
        $this->type = $type;
        $this->prixunit = $prixunit;
    }
    
    public function getNumPlat(): int {
        return $this->numplat;
    }

    public function getLibelle(): string {
        return $this->libelle;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getPrixUnit(): float {
        return $this->prixunit;
    }

}