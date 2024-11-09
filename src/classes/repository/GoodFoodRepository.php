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

    // retourne la liste des plats (numéro et nom du plat) du restaurant
    public function getAllCommandeNumbers(): array
    {
        $query = "SELECT numcom FROM COMMANDE GROUP BY numcom ASC";
        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            ORDER BY p.numplat ASC
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

    // 4. Affichage (dans un ordre décroissant) du chiffre d’affaire et le nombre de commandes réalisés
    // par chaque serveur (nom, chiffre d’affaire, nombre de commandes) en une période donnée (date début, date fin).
    public function getChiffreAffaireEtCommandesParServeur(string $dateStart, string $dateEnd): array
    {
        $query = "
            SELECT s.nomserv,
                   COUNT(co.numcom) AS nombre_commandes,
                   SUM(co.montcom) AS chiffre_affaire
            FROM SERVEUR s
            JOIN AFFECTER a ON s.numserv = a.numserv
            JOIN COMMANDE co ON a.numtab = co.numtab AND a.dataff = co.datcom
            WHERE co.datcom BETWEEN :dateStart AND :dateEnd
            GROUP BY s.nomserv
            ORDER BY chiffre_affaire DESC
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dateStart' => $dateStart, ':dateEnd' => $dateEnd]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Affichage de la liste des serveurs (nom) n’ayant pas réalisé de chiffre d’affaire
    //  durant une période donnée (date début, date fin).
    public function getServeursSansChiffreAffaire(string $dateStart, string $dateEnd): array
    {
        $query = "
        SELECT s.nomserv
        FROM serveur s
        LEFT JOIN affecter a ON s.numserv = a.numserv
        LEFT JOIN commande c ON a.numtab = c.numtab
        WHERE (c.datcom IS NULL OR c.datcom NOT BETWEEN :dateStart AND :dateEnd)
        AND (a.dataff IS NULL OR a.dataff NOT BETWEEN :dateStart AND :dateEnd)
        GROUP BY s.nomserv;

    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dateStart' => $dateStart, ':dateEnd' => $dateEnd]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Calcul du montant total d’une commande donnée (numéro de commande) et la mise à jour de la table COMMAND
    public function calculMontantCommande(int $numCommande): float|bool
    {
        // obtenir le montant total de la commande
        $query = "
        SELECT SUM(p.prixunit * c.quantite) AS montant_total
        FROM CONTIENT c
        JOIN PLAT p ON c.numplat = p.numplat
        WHERE c.numcom = :numCommande
    ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':numCommande' => $numCommande]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // si la commande n'existe pas ou si le montant total est NULL
        if (!$result || is_null($result['montant_total'])) {
            return false;
        }

        $montantTotal = (float) $result['montant_total'];

        //  mettre à jour le montant total de la commande
        $updateQuery = "UPDATE COMMANDE SET montcom = :montantTotal WHERE numcom = :numCommande";
        $updateStmt = $this->pdo->prepare($updateQuery);
        $updateStmt->execute([':montantTotal' => $montantTotal, ':numCommande' => $numCommande]);

        return $montantTotal;
    }

}
