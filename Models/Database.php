<?php
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private string $host;
    private string $dbname;
    private string $user;
    private string $password;
    private string $charset;

    private function __construct()
    {
        $this->host = "localhost";
        $this->dbname = defined('DB_NAME') ? DB_NAME : "llamasdefuego";
        $this->user = defined('DB_USER') ? DB_USER : "root";
        $this->password = defined('DB_PASSWORD') ? DB_PASSWORD : "";
        $this->charset = "utf8mb4";

        $this->pdo = $this->connect();
    }
    
    public static function getInstance() : Database
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function pdo() : PDO
    {
        return $this->pdo;
    }

    private function connect() : PDO
    {
        try {
            $dns = "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=".$this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $pdo = new PDO($dns, $this->user, $this->password, $options);

            return $pdo;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function __serialize(): array
    {
        return array();
    }
}
?>