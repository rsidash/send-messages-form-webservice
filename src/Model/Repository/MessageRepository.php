<?php

namespace App\Model\Repository;

use App\Model\Entity\Message;
use App\services\SendStatus;
use config\Database;
use PDO;
use Exception;

class MessageRepository
{
    private ?PDO $connection;
    private string $table;
    private array $statuses;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();

        $this->table = 'messages';
        $this->statuses = SendStatus::getSendStatus();
    }

    public function getAll(string $orderByDirection): array
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

    public function filterByStatus(string $status, string $orderByDirection): array
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

    public function getNotSend(): array
    {
        $result = [];

        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND is_send = {$this->statuses['notSend']['statusCode']};";
        $stmt = $this->connection->query($sql);

        $records = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($records as $record) {
            $result[] = new Message(json_decode(json_encode($record),true));
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function getNotSendById(int $id): Message
    {
        $result = [];

        $sql = "SELECT * FROM {$this->table}
                WHERE deleted_at IS NULL
                AND is_send = {$this->statuses['notSend']['statusCode']}
                AND id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("id", $id);

        $stmt->execute();

        $record = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$record) {
            throw new Exception('No data found');
        }

        return new Message(json_decode(json_encode($record),true));
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

    public function deleteNotSend(): bool
    {
        $sql = "UPDATE {$this->table}
                SET deleted_at = SYSDATE()
                WHERE is_send = {$this->statuses['notSend']['statusCode']}";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute();

        return true;
    }

    public function deleteById(int $id): bool
    {
        $sql = "UPDATE {$this->table}
                SET deleted_at = SYSDATE()
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("id", $id);

        $stmt->execute();

        return true;
    }
}
