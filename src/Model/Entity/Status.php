<?php

namespace App\Model\Entity;

class Status extends AbstractModel
{
    private ?int $id;
    private ?string $title;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getTitle(),
        ];
    }
}