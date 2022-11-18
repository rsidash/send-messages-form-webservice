<?php

namespace App\Model\Entity;

use App\services\SendStatus;

class Message extends AbstractModel
{
    private ?int $id;
    private ?string $text;
    private ?bool $isSend;
    private ?string $createdAt;
    private ?string $updatedAt;
    private ?string $reason;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->isSend = $data['is_send'] ?? 0;
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

    public function getIsSend(): bool
    {
        return $this->isSend;
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

    public function getStatus(): string
    {
        $isSend = $this->getIsSend();
        $statuses = SendStatus::getSendStatus();

        if ($isSend) {
            return $statuses['send']['title'];
        }

        return $statuses['notSend']['title'];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'date' => $this->getLastSendDate(),
            'status' => $this->getStatus(),
            'reason' => $this->getReason(),
        ];
    }
}
