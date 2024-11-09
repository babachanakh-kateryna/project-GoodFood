<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\rest\lists\Plat;
use iutnc\deefy\rest\lists\PlatList;
use iutnc\deefy\rest\lists\Serveur;
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

    // Détermination de la liste des plats (numéro et nom du plat) servis à une  période donnée (date début, date fin).
    public function getPlatsServisDansPeriode(string $dateStart, string $dateEnd): PlatList
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

        $platList = new PlatList();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $plat = new Plat( $row['numplat'], $row['libelle'], $row['type'],  $row['prixunit']);
            $platList->addPlat($plat);
        }

        return $platList;
    }

}
