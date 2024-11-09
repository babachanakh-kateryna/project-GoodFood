<?php
declare(strict_types=1);

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action as act;
use iutnc\deefy\render\Renderer;

/**
 * Class Dispatcher
 */
class Dispatcher
{
    private ?string $action = null;

    function __construct()
    {
        $this->action = isset($_GET['action']) ? htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8') : 'default';
    }

    public function run(): void
    {
        $html = '';
        switch ($this->action) {
            case 'default':
                $action = new act\DefaultAction();
                $html = $action->execute();
                break;
            case 'getPlatsServis':
                $action = new act\ActionPlatsServis();
                $html = $action->execute();
                break;
            case 'getPlatsNonCommandes':
                $action = new act\ActionPlatsNonCommandes();
                $html = $action->execute();
                break;
            case 'getServeursParTable':
                $action = new act\ActionServeursParTable();
                $html = $action->execute();
                break;
            case 'getChiffreAffaireServeurs':
                $action = new act\ActionChiffreAffaireServeurs();
                $html = $action->execute();
                break;
            case 'getServeursSansChiffreAffaire':
                $action = new act\ActionServeursSansChiffreAffaire();
                $html = $action->execute();
                break;
            case 'getCalculMontantCommande':
                $action = new act\ActionCalculMontantCommande();
                $html = $action->execute();
                break;

        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Good Food</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="/css/styles.css"> 
        </head>
        <body>
            <div class="sidebar">
                <a href="?action=default">Accueil</a>
                <a href="?action=getPlatsServis">Détermination des plats servis</a>
                <a href="?action=getPlatsNonCommandes">Plats jamais commandés</a>
                <a href="?action=getServeursParTable">Serveurs par table</a>
                <a href="?action=getChiffreAffaireServeurs">Chiffre d'affaire par serveur</a>
                <a href="?action=getServeursSansChiffreAffaire">Serveurs sans chiffre d'affaire</a>
                <a href="?action=getCalculMontantCommande">Calcul du montant total d'une commande</a>
            </div>
            
            <div class="content">
                $html
            </div>
        </body>
        </html>
HTML;
    }
}
