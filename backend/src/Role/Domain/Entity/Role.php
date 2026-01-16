<?php

namespace Dadinaks\Role\Domain\Entity;

use Symfony\Component\Uid\Uuid;

final class Role
{
    private string $uid;

    private string $role;

    private string $label;

    private ?string $description = null;

    public function __construct(string $role, string $label, ?string $description)
    {
        $this->uid = Uuid::v7()->toString();
        $this->role = $role;
        $this->label = $label;
        $this->description = $description;
    }

    public static function fromState(array $state): self
    {
        $role       = new self($state['role'], $state['label'], $state['description']);
        $role->uid  = $state['uid'];

        return $role;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
