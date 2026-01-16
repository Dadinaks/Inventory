<?php

namespace Dadinaks\Role\Infrastructure\Persistence\Entity;

use Dadinaks\Role\Domain\Entity\Role;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'role')]
final class RoleOrm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    private string $uid;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $role;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $label;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public static function fromDomain(Role $role): self
    {
        $orm = new self();
        $orm->uid         = $role->getUid();
        $orm->role        = $role->getRole();
        $orm->label       = $role->getLabel();
        $orm->description = $role->getDescription();

        return $orm;
    }

    public function toDomain(): Role
    {
        return Role::fromState([
            'uid'       => $this->uid,
            'role'      => $this->role,
            'label'      => $this->label,
            'description'  => $this->description
        ]);
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
