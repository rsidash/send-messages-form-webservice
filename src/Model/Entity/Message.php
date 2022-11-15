<?php

namespace App\Model\Entity;

class Message extends AbstractModel
{
    private ?int $id;
    private ?string $text;
    private ?int $statusId;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->statusId = $data['status_id'] ?? null;
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'status_id' => $this->getStatusId(),
        ];
    }
}
