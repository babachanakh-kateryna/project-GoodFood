<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\GoodFoodRepository;

/**
 * Class ActionPlatsNonCommandes - 2. Affichage de la liste des plats (numéro et nom du plat)
 * qui n’ont jamais été commandés pendant une période donnée (date début, date fin).
 */
class ActionPlatsNonCommandes extends Action
{
    /**
     * Exécute l'action pour afficher les plats non commandés
     *
     * @return string HTML avec la liste des plats ou un message si aucun plat trouvé
     * @throws \Exception
     */
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

        // Si aucun plat non commandé trouvé
        if (empty($plats)) {
            return "Tous les plats ont été commandés dans cette période.";
        }

        // Afficher les plats non commandés
        $html = "<h2>Plats non commandés entre $dateStart et $dateEnd</h2><ul>";
        foreach ($plats as $plat) {
            $html .= "<li>{$plat['numplat']} - {$plat['libelle']}</li>";
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