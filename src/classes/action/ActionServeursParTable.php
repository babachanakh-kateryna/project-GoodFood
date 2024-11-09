<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\GoodFoodRepository;

/**
 * Class ActionServeursParTable - 3. Etablissement de la liste serveurs (nom et date)
 * qui ont servi une table donnée à une période donnée (date début, date fin).
 */
class ActionServeursParTable extends Action
{

    public function execute(): string
    {
        // obtenir les dates de début et de fin et le numéro de la table
        $numtab = $_GET['numtab'] ?? null;
        $dateStart = $_GET['date_start'] ?? null;
        $dateEnd = $_GET['date_end'] ?? null;

        if (is_null($numtab) || is_null($dateStart) || is_null($dateEnd)) {
            return $this->renderForm();
        }

        // obtenir les serveurs ayant servi la table dans la période
        $serveurs = GoodFoodRepository::getInstance()->getServeursByTableAndPeriod($numtab, $dateStart, $dateEnd);

        // si aucun serveur n'a servi la table dans cette période
        if (empty($serveurs)) {
            return "Aucun serveur n'a servi la table $numtab entre $dateStart et $dateEnd.";
        }

        // afficher les serveurs ayant servi la table
        $html = "<h2>Serveurs ayant servi la table $numtab entre $dateStart et $dateEnd</h2><ul>";
        foreach ($serveurs as $serveur) {
            $html .= "<li>{$serveur['nomserv']} - Date d'affectation: {$serveur['date_affectation']}</li>";
        }
        $html .= "</ul>";

        return $html;
    }

    // form pour entrer la période
    private function renderForm(): string
    {
        $tables = GoodFoodRepository::getInstance()->getAllTables();

        $options = '';
        foreach ($tables as $table) {
            $options .= "<option value=\"$table\">Table $table</option>";
        }

        return <<<HTML
        <h2>Entrez le numéro de la table et la période</h2>
        <form method="get" action="">
            <input type="hidden" name="action" value="getServeursParTable">
            <label for="numtab">Numéro de la table :</label>
            <select id="numtab" name="numtab" required>
                $options
            </select>
            <br>
            <label for="date_start">Date de début :</label>
            <input type="date" id="date_start" name="date_start" required>
            <br>
            <label for="date_end">Date de fin :</label>
            <input type="date" id="date_end" name="date_end" required>
            <br><br>
            <button type="submit">Voir les serveurs</button>
        </form>
HTML;
    }
}