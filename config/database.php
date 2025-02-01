<?php
class Database
{
    // private $host = "localhost";
    // private $db_name = "codepixel_gup_ems";
    // private $username = "codepixel_gup_ems_user";
    // private $password = "W7m**Mrr6WrvMjJ8";
    private $host = "localhost";
    private $db_name = "event_management";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
