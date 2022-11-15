<?php

namespace App\Model\Repository;

use App\Model\Entity\Message;
use config\Database;
use PDO;

class MessageRepository
{
    private ?PDO $connection;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function create(array $data): Message
    {
        $sql = "INSERT INTO messages (text, 
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
