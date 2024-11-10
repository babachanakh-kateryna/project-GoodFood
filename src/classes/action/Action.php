<?php

namespace iutnc\deefy\action;

/**
 * Class Action - Classe abstraite représentant une action
 * Les classes concrètes doivent implémenter la méthode execute().
 */
abstract class Action {

    /**
     * @var string|null Méthode HTTP de la requête (GET, POST, etc.)
     */
    protected ?string $http_method = null;
    /**
     * @var string|null Nom d'hôte du serveur
     */
    protected ?string $hostname = null;
    /**
     * @var string|null Nom du script
     */
    protected ?string $script_name = null;

    /**
     * Constructeur qui initialise les informations de la requête HTTP
     */
    public function __construct(){

        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Méthode à implémenter par les classes filles pour exécuter l'action.
     *
     * @return string HTML à afficher
     */
    abstract public function execute() : string;

}
