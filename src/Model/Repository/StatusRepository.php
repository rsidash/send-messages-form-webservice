<?php

namespace App\Model\Repository;

use App\Model\Entity\Status;
use config\Database;
use Exception;
use PDO;

class StatusRepository
{
    private ?PDO $connection;
    private string $table;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
        $this->table = 'statuses';
    }

    public function getAll()
    {
        $result = [];

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL;";
        $stmt = $this->connection->query($sql);

        $records = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($records as $record) {
            $result[] = new Status(json_decode(json_encode($record),true));
        }

        return $result;
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND id = :id;";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("id", $id);
        $stmt->execute();

        $record = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$record) {
            throw new Exception('No data found');
        }

        return new Status(json_decode(json_encode($record),true));
    }
}