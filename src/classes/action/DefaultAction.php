<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthProvider;
use iutnc\deefy\repository\GoodFoodRepository;

/**
 * Class DefaultAction est une classe qui represente l'action par defaut
 */
class DefaultAction extends Action
{
    public function execute(): string
    {
        return <<<HTML
            <h1>Bienvenue sur le site de gestion de la base de données GOODFOOD !</h1>
            <p>À l’aide du menu de navigation à gauche, vous pouvez accéder aux fonctionnalités suivantes pour manipuler les données de la base de données de GOODFOOD :</p>
            <ul>
                <li><strong>Détermination des plats servis :</strong> Affichez la liste des plats (numéro et nom) qui ont été servis pendant une période donnée (date de début et date de fin).</li>
                <li><strong>Plats jamais commandés :</strong> Affichez la liste des plats (numéro et nom) qui n'ont jamais été commandés pendant une période donnée.</li>
                <li><strong>Serveurs par table :</strong> Obtenez la liste des serveurs (nom et date) ayant servi une table spécifique pendant une période donnée.</li>
                <li><strong>Chiffre d'affaire par serveur :</strong> Affichez, en ordre décroissant, le chiffre d'affaire et le nombre de commandes réalisés par chaque serveur pour une période donnée.</li>
                <li><strong>Serveurs sans chiffre d'affaire :</strong> Affichez la liste des serveurs qui n'ont pas réalisé de chiffre d'affaire durant une période donnée.</li>
                <li><strong>Calcul du montant total d'une commande :</strong> Calculez le montant total d'une commande spécifique (numéro de commande) et mettez à jour la table des commandes avec le montant calculé.</li>
            </ul>
            <p>Utilisez les liens dans le menu pour accéder à chacune de ces fonctionnalités.</p>
        HTML;
    }
}