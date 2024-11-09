<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\GoodFoodRepository;

class ActionPlatsNonCommandes extends Action
{
    public function execute(): string
    {
        if (!isset($_GET['date_start']) || !isset($_GET['date_end'])) {
            return $this->renderForm();
        }

        // obtenir les dates de début et de fin
        $dateStart = $_GET['date_start'];
        $dateEnd = $_GET['date_end'];

        // obtenir les plats servis dans la période
        $plats = GoodFoodRepository::getInstance()->getPlatsNonCommandes($dateStart, $dateEnd);

        /// si aucun plat n'a été servi dans cette période
        if (empty($plats)) {
            return "Tous les plats ont été commandés dans cette période.";
        }

        // afficher les plats servis
        $html = "<h2>Plats non commandés entre $dateStart et $dateEnd</h2><ul>";
        foreach ($plats as $plat) {
            $html .= "<li>{$plat->getNumPlat()} - {$plat->getLibelle()}</li>";
        }
        $html .= "</ul>";

        return $html;
    }

    // form pour entrer la période
    private function renderForm(): string
    {
        return <<<HTML
        <h2>Entrez la période pour les plats non commandés</h2>
        <form method="get" action="">
            <input type="hidden" name="action" value="getPlatsNonCommandes">
            <label for="date_start">Date de début :</label>
            <input type="date" id="date_start" name="date_start" required>
            <br>
            <label for="date_end">Date de fin :</label>
            <input type="date" id="date_end" name="date_end" required>
            <br><br>
            <button type="submit">Voir les plats non commandés</button>
        </form>
HTML;
    }

}