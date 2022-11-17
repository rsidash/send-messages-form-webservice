<?php

namespace App\Model\Entity;

use App\Model\Repository\StatusRepository;
use Exception;

class Message extends AbstractModel
{
    private ?int $id;
    private ?string $text;
    private ?int $statusId;
    private ?string $createdAt;
    private ?string $updatedAt;
    private ?string $reason;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->statusId = $data['status_id'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;
        $this->reason = $data['reason'] ?? null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text ?? '';
    }

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getLastSendDate(): string
    {
        $createdAt = strtotime($this->getCreatedAt());
        $updatedAt = strtotime($this->getUpdatedAt());

        if ($updatedAt > $createdAt) {
            return $this->getUpdatedAt();
        }

        return $this->getCreatedAt();
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getStatus(): ?Status
    {
        $statusId = $this->getStatusId();
        $statusRepo = new StatusRepository();

        try {
            return $statusRepo->getById($statusId);
        } catch (Exception $e) {
            return null;
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'date' => $this->getLastSendDate(),
            'status' => $this->getStatus()->getTitle(),
            'reason' => $this->getReason(),
        ];
    }
}
