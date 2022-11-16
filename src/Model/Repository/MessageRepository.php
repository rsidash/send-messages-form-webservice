<?php

namespace App\Model\Repository;

use App\Model\Entity\Message;
use config\Database;
use PDO;

class MessageRepository
{
    private ?PDO $connection;
    private string $table;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
        $this->table = 'messages';
    }

    public function getAll(string $orderByDirection)
    {
        $result = [];

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY created_at {$orderByDirection};";
        $stmt = $this->connection->query($sql);

        $records = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($records as $record) {
            $result[] = new Message(json_decode(json_encode($record),true));
        }

        return $result;
    }

    public function filterByStatus(int $statusId, string $orderByDirection)
    {
        $result = [];

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND status_id = :statusId ORDER BY created_at {$orderByDirection};";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("statusId", $statusId);

        $stmt->execute();

        $records = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($records as $record) {
            $result[] = new Message(json_decode(json_encode($record),true));
        }

        return $result;
    }

    public function create(array $data): Message
    {
        $sql = "INSERT INTO {$this->table} (text, 
                                    status_id) 
                VALUES (:text, 
                        :status_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("text", $data['text']);
        $stmt->bindValue("status_id", $data['status_id']);

        $stmt->execute();

        return new Message($data);
    }
}
