<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\GoodFoodRepository;

/**
 * Class ActionServeursSansChiffreAffaire - 5. Affichage de la liste des serveurs (nom)
 * n’ayant pas réalisé de chiffre d’affaire durant une période donnée (date début, date fin).
 */
class ActionServeursSansChiffreAffaire extends Action
{
    /**
     * Exécute l'action pour afficher les serveurs sans chiffre d'affaire
     *
     * @return string HTML avec la liste des serveurs ou un message si aucun serveur trouvé
     * @throws \Exception
     */
    public function execute(): string
    {
        $dateStart = $_GET['date_start'] ?? null;
        $dateEnd = $_GET['date_end'] ?? null;

        if (is_null($dateStart) || is_null($dateEnd)) {
            return $this->renderForm();
        }

        // obtenir les serveurs sans chiffre d'affaire dans la période
        $serveurs = GoodFoodRepository::getInstance()->getServeursSansChiffreAffaire($dateStart, $dateEnd);

        // si tous les serveurs ont réalisé un chiffre d'affaire dans cette période
        if (empty($serveurs)) {
            return "Tous les serveurs ont réalisé un chiffre d'affaire durant cette période.";
        }

        // afficher les serveurs sans chiffre d'affaire
        $html = "<h2>Serveurs sans chiffre d'affaire entre $dateStart et $dateEnd</h2><ul>";
        foreach ($serveurs as $serveur) {
            $html .= "<li>{$serveur['nomserv']}</li>";
        }
        $html .= "</ul>";

        return $html;
    }


    /**
     * Affiche le formulaire pour sélectionner la période
     *
     * @return string HTML du formulaire de sélection de période
     */
    private function renderForm(): string
    {
        return <<<HTML
        <h2>Entrez la période pour voir les serveurs sans chiffre d'affaire</h2>
        <form method="get" action="">
            <input type="hidden" name="action" value="getServeursSansChiffreAffaire">
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