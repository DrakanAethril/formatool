<?php

namespace App\Entity;

use App\Repository\UsersTrainingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersTrainingsRepository::class)]

class UsersTrainings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'usersTrainings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'usersTrainings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trainings $training = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private array $role = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $perms = null;

    #[ORM\UniqueConstraint(
        name: 'user_training_unique_idx',
        columns: ['user', 'training']
    )]

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

    public function getTraining(): ?Trainings
    {
        return $this->training;
    }

    public function setTraining(?Trainings $training): static
    {
        $this->training = $training;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPerms(): ?string
    {
        return $this->perms;
    }

    public function setPerms(?string $perms): static
    {
        $this->perms = $perms;

        return $this;
    }
}
