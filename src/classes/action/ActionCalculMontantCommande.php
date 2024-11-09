<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\repository\GoodFoodRepository;

/**
 * Class ActionCalculMontantCommande - 6. Calcul du montant total d’une commande donnée (numéro de commande)
 * et la mise à jour de la table COMMAND
 */
class ActionCalculMontantCommande extends Action
{
    public function execute(): string
    {
        $numCommande = $_GET['numCommande'] ?? null;

        if (is_null($numCommande)) {
            return $this->renderForm();
        }

        // calculer le montant total de la commande
        $montantTotal = GoodFoodRepository::getInstance()->calculMontantCommande($numCommande);

        if ($montantTotal === false) {
            return "Commande non trouvée ou erreur lors du calcul.";
        }

        return "<h2>Montant total de la commande $numCommande: $montantTotal €</h2>";
    }

    //form pour entrer le numéro de commande
    private function renderForm(): string
    {
        $commandes = GoodFoodRepository::getInstance()->getAllCommandeNumbers();

        $options = '';
        foreach ($commandes as $commande) {
            $options .= "<option value=\"{$commande['numcom']}\">Commande {$commande['numcom']}</option>";
        }

        return <<<HTML
        <h2>Calculer le montant total d'une commande</h2>
        <form method="get" action="">
            <input type="hidden" name="action" value="getCalculMontantCommande">
            <label for="numCommande">Numéro de commande :</label>
            <select id="numCommande" name="numCommande" required>
                $options
            </select>
            <br><br>
            <button type="submit">Calculer le montant</button>
        </form>
HTML;
    }
}