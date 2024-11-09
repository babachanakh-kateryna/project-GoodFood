<?php

namespace iutnc\deefy\rest\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

/**
 * Class PlatList is a class that represents a list of plats
 */
class PlatList
{
    private $plats = [];

    public function addPlat(Plat $plat)
    {
        $this->plats[] = $plat;
    }

    public function __get(string $attribut): mixed
    {
        if (property_exists($this, $attribut))
            return $this->$attribut;
        throw new InvalidPropertyNameException(" $attribut : invalide propriete");
    }
    public function getPlats()
    {
        return $this->plats;
    }

}