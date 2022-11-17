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

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY updated_at {$orderByDirection};";
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

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND status_id = :statusId ORDER BY updated_at {$orderByDirection};";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("statusId", $statusId);

        $stmt->execute();

        $records = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($records as $record) {
            $result[] = new Message(json_decode(json_encode($record),true));
        }

        return $result;
    }

    public function getNotSend()
    {
        $result = [];

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND status_id = 2;";
        $stmt = $this->connection->query($sql);

        $records = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($records as $record) {
            $result[] = new Message(json_decode(json_encode($record),true));
        }

        return $result;
    }

    public function create(array $data): Message
    {
        $sql = "INSERT INTO {$this->table} (text, 
                                    status_id,
                                    reason) 
                VALUES (:text, 
                        :status_id,
                        :reason)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("text", $data['text']);
        $stmt->bindValue("status_id", $data['status_id']);
        $stmt->bindValue("reason", $data['reason']);

        $stmt->execute();

        return new Message($data);
    }

    public function update(array $data): Message
    {
        $sql = "UPDATE {$this->table} 
                SET status_id = :status_id,
                    updated_at = SYSDATE(),
                    reason = :reason
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("status_id", $data['status_id']);
        $stmt->bindValue("id", $data['id']);
        $stmt->bindValue("reason", $data['reason']);

        $stmt->execute();

        return new Message($data);
    }
}
