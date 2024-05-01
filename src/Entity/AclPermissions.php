<?php

namespace App\Entity;

use App\Repository\AclPermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AclPermissionsRepository::class)]
class AclPermissions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'aclPermissions')]
    private ?Users $user = null;

    #[ORM\Column]
    private ?int $subjectId = null;

    #[ORM\Column(length: 50)]
    private ?string $resource = null;

    #[ORM\Column(length: 50)]
    private ?string $privilege = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function setSubjectId(int $subjectId): static
    {
        $this->subjectId = $subjectId;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getPrivilege(): ?string
    {
        return $this->privilege;
    }

    public function setPrivilege(string $privilege): static
    {
        $this->privilege = $privilege;

        return $this;
    }
}
