<?php

include_once('inc/config.inc.php');

class Database
{
    /** @var PDO|null */
    private $pdo;

    public function __construct()
    {
        global $db;

        $host = $db['server'];
        $database = $db['database'];
        $user = $db['user'];
        $pass = $db['pass'];

        if (! extension_loaded('pdo')) {
            die('ERROR: PDO extension is not available on this server.');
        }

        if (! extension_loaded('pdo_mysql')) {
            die('ERROR: PDO MySQL driver is not enabled.');
        }

        $dsn = "mysql:host=$host;dbname=$database;charset=utf8";

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);
        } catch (PDOException $e) {
            die('PDO Connection failed: ' . $e->getMessage());
        }
    }

    public function query($sql, $params = [], $mode = null)
    {
        if (! $this->pdo) {
            die('PDO not initialized.');
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        switch ($mode) {
            case 'one':
                return $stmt->fetch();
            case 'value':
                return $stmt->fetchColumn();
            default:
                return $stmt->fetchAll();
        }
    }
}
