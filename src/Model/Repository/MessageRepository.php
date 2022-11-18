<?php

namespace App\Model\Repository;

use App\Model\Entity\Message;
use App\services\SendStatus;
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

    public function filterByStatus(string $status, string $orderByDirection)
    {
        $result = [];

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND is_send = :isSend ORDER BY updated_at {$orderByDirection};";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("isSend", $status);

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
        $statuses = SendStatus::getSendStatus();

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND is_send = {$statuses['notSend']['statusCode']};";
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
                                    is_send,
                                    reason) 
                VALUES (:text, 
                        :is_send,
                        :reason)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("text", $data['text']);
        $stmt->bindValue("is_send", $data['is_send']);
        $stmt->bindValue("reason", $data['reason']);

        $stmt->execute();

        return new Message($data);
    }

    public function update(array $data): Message
    {
        $sql = "UPDATE {$this->table} 
                SET is_send = :is_send,
                    updated_at = SYSDATE(),
                    reason = :reason
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("is_send", $data['is_send']);
        $stmt->bindValue("id", $data['id']);
        $stmt->bindValue("reason", $data['reason']);

        $stmt->execute();

        return new Message($data);
    }
}
