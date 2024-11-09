<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthProvider;
use iutnc\deefy\repository\GoodFoodRepository;

/**
 * Class DefaultAction est une classe qui represente l'action par defaut
 */
class DefaultAction extends Action
{
    public function executeGet(): string
    {
        return "<h1>Bienvenue !</h1>";
    }

    public function executePost(): string
    {
        return "Aucune action selectionnée ou action invalide";
    }
}