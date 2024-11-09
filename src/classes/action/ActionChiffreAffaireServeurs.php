<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\GoodFoodRepository;

/**
 * Class ActionChiffreAffaireServeurs - 4. Affichage (dans un ordre décroissant)
 * du chiffre d’affaire et le nombre de commandes réalisés
 * par chaque serveur (nom, chiffre d’affaire, nombre de commandes) en une période donnée (date début, date fin).
 */
class ActionChiffreAffaireServeurs extends Action
{
    public function execute(): string
    {
        // obtenir les dates de début et de fin et le numéro de la table
        $dateStart = $_GET['date_start'] ?? null;
        $dateEnd = $_GET['date_end'] ?? null;

        if (is_null($dateStart) || is_null($dateEnd)) {
            return $this->renderForm();
        }

        // obtenir le chiffre d'affaire et le nombre de commandes par serveur dans la période
        $serveurs = GoodFoodRepository::getInstance()->getChiffreAffaireEtCommandesParServeur($dateStart, $dateEnd);

        // si aucun serveur n'a réalisé de chiffre d'affaire dans cette période
        if (empty($serveurs)) {
            return "Aucun serveur n'a réalisé de chiffre d'affaire dans cette période.";
        }

        // afficher le chiffre d'affaire et le nombre de commandes par serveur
        $html = "<h2>Chiffre d'affaire et nombre de commandes par serveur entre $dateStart et $dateEnd</h2>";
        $html .= "<table><tr><th>Serveur</th><th>Nombre de Commandes</th><th>Chiffre d'Affaire (€)</th></tr>";
        foreach ($serveurs as $serveur) {
            $html .= "<tr><td>{$serveur['nomserv']}</td><td>{$serveur['nombre_commandes']}</td><td>{$serveur['chiffre_affaire']}</td></tr>";
        }
        $html .= "</table>";

        return $html;
    }

    // form pour entrer la période
    private function renderForm(): string
    {
        return <<<HTML
        <h2>Entrez la période pour voir le chiffre d'affaire par serveur</h2>
        <form method="get" action="">
            <input type="hidden" name="action" value="getChiffreAffaireServeurs">
            <label for="date_start">Date de début :</label>
            <input type="date" id="date_start" name="date_start" required>
            <br>
            <label for="date_end">Date de fin :</label>
            <input type="date" id="date_end" name="date_end" required>
            <br><br>
            <button type="submit">Voir les résultats</button>
        </form>
HTML;
    }
}