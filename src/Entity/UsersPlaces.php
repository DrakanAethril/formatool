<?php

namespace App\Entity;

use App\Config\UsersRolesPlacesEnum;
use App\Config\UsersStatusPlacesEnum;
use App\Repository\UsersPlacesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersPlacesRepository::class)]

class UsersPlaces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'usersPlaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'usersPlaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Places $place = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $permissions = null;

    #[ORM\UniqueConstraint(
        name: 'user_place_unique_idx',
        columns: ['user', 'place']
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

    public function getPlace(): ?Places
    {
        return $this->place;
    }

    public function setPlace(?Places $place): static
    {
        $this->place = $place;

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

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPermissions(): ?string
    {
        return $this->permissions;
    }

    public function setPermissions(?string $permissions): static
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function getEnumStatusObject() {
        return UsersStatusPlacesEnum::tryFrom($this->getStatus());
    }

    public function getRolesEnumForDisplay() : array {
        $res = [];
        if(!empty($this->getRoles())) {
            foreach($this->getRoles() as $role) {
                $res[] = UsersRolesPlacesEnum::tryFrom($role);
            }
        }
        return $res;
    }

    public function generatePermsBasedOnRole(string $role):array {
        $res = [];
        switch($role) {
            case UsersRolesPlacesEnum::TEACHER->value:

            break;
            case UsersRolesPlacesEnum::STAFF->value:

            break;
            case UsersRolesPlacesEnum::MANAGEMENT->value:
                //$res[] = 'PLACE_ALL|ALL|'.$this->getPlace()->getId();
            break;
            case UsersRolesPlacesEnum::ADMIN->value:
                $res[] = 'PLACE_ALL|ALL|'.$this->getPlace()->getId();
            break;
            default:
        }
        return $res;
    }
}
