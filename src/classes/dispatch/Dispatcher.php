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
            <title>Good Food<</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        </head>
        <body>
        
            <div class="sidebar">
                <a href="?action=default">Home</a>
                <a href="?action=getPlatsServis">PlatsServis</a>
                <a href="?action=getPlatsNonCommandes">PlatsNonCommandes</a>
                <a href="?action=getServeursParTable">ServeursParTable</a>
            </div>
            
            <div class="content">
                $html
            </div>
        
        </body>
        </html>
    HTML;
    }

}
