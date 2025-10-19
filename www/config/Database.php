<?php
require_once __DIR__ . '../vendor/autoload.php';

class Database
{

    private $host;
    private $username = "root";
    private $password = "";
    private $dbname = "www";
    private $conn;

    public function __construct()
    {

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // Now you can access environment variables
        $host = getenv('DB_HOST');
        $password = $_ENV['DB_PASSWORD'];

        echo "Database Host: " . $databaseHost . "\n";
        echo "API Key: " . $apiKey . "\n";
        
        $this->host = getenv('DB_HOST') ?: 'localhost';
        
    }

    // Connect to the Database
    public function connect()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);

            // get any error information while trying to connect
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
