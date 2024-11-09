<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\tables\Plat;
use PDO;

/**
 * Class GoodFoodRepository est une classe qui manipule les données de la base de données
 */
class GoodFoodRepository
{
    private \PDO $pdo;
    private static ?GoodFoodRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        $this->pdo = new PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getInstance(): self
    {
        // Check if the configuration is set
        if (empty(self::$config)) {
            throw new \Exception("Database configuration is not set");
        }
        if (is_null(self::$instance)) {
            self::$instance = new GoodFoodRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $file): void
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }

        if (!isset($conf['host'], $conf['dbname'], $conf['username'], $conf['password'])) {
            throw new \Exception("Configuration file is missing required database parameters.");
        }

        self::$config = [
            'dsn' => sprintf("mysql:host=%s;dbname=%s;charset=utf8", $conf['host'], $conf['dbname']),
            'user' => $conf['username'],
            'pass' => $conf['password'],
        ];
    }

    // retourne la liste des tables (numéro de table) du restaurant
    public function getAllTables(): array
    {
        $query = "SELECT numtab FROM TABL";
        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // 1. Détermination de la liste des plats (numéro et nom du plat) servis à une période donnée (date début, date fin).
    public function getPlatsServis(string $dateStart, string $dateEnd): array
    {
        $query = "
            SELECT DISTINCT p.numplat, p.libelle, p.type, p.prixunit
            FROM PLAT p
            JOIN CONTIENT c ON p.numplat = c.numplat
            JOIN COMMANDE co ON c.numcom = co.numcom
            WHERE co.datcom BETWEEN :dateStart AND :dateEnd
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dateStart' => $dateStart, ':dateEnd' => $dateEnd]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Affichage de la liste des plats (numéro et nom du plat) qui n’ont jamais été  commandés pendant une période donnée (date début, date fin).
    public function getPlatsNonCommandes(string $dateStart, string $dateEnd): array
    {
        $query = "
            SELECT p.numplat, p.libelle, p.type, p.prixunit
            FROM PLAT p
            WHERE p.numplat NOT IN (
                SELECT DISTINCT co.numplat
                FROM CONTIENT co
                JOIN COMMANDE c ON co.numcom = c.numcom
                WHERE c.datcom BETWEEN :dateStart AND :dateEnd
            );
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dateStart' => $dateStart, ':dateEnd' => $dateEnd]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Etablissement de la liste serveurs (nom et date) qui ont servi une table donnée  à une période donnée (date début, date fin).
    public function getServeursByTableAndPeriod(int $numtab, string $dateStart, string $dateEnd): array
    {
        $query = "
            SELECT DISTINCT s.nomserv, a.dataff AS date_affectation
            FROM SERVEUR s
            JOIN AFFECTER a ON s.numserv = a.numserv
            WHERE a.numtab = :numtab
              AND a.dataff BETWEEN :dateStart AND :dateEnd
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':numtab' => $numtab, ':dateStart' => $dateStart, ':dateEnd' => $dateEnd]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
