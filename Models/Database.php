<?php
class Database
{
    private string $host;
    private string $dbname;
    private string $user;
    private string $password;
    private string $charset;

    public function __construct() {
        $this->host = "localhost";
        $this->dbname = "AppwebMVC";
        $this->user = "root";
        $this->password = "";
        $this->charset = "utf8mb4";
    }

    public function connect() : PDO {
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
}
?>