<?php

namespace App\Entity;

use App\Config\UsersRolesTrainingsEnum;
use App\Config\UsersStatusTrainingsEnum;
use App\Repository\UsersTrainingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private array $roles = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $permissions = null;

    /**
     * @var Collection<int, TrainingsOptions>
     */
    #[ORM\ManyToMany(targetEntity: TrainingsOptions::class, inversedBy: 'usersTrainings')]
    private Collection $trainingOptions;

    public function __construct()
    {
        $this->trainingOptions = new ArrayCollection();
    }

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

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }


    public function getEnumStatusObject() {
        return UsersStatusTrainingsEnum::tryFrom($this->getStatus());
    }

    public function getRolesEnumForDisplay() : array {
        $res = [];
        if(!empty($this->getRoles())) {
            foreach($this->getRoles() as $role) {
                $res[] = UsersRolesTrainingsEnum::tryFrom($role);
            }
        }
        return $res;
    }

    public function generatePermsBasedOnRole(string $role):array {
        $res = [];
        switch($role) {
            case UsersRolesTrainingsEnum::TEACHER->value:

            break;
            case UsersRolesTrainingsEnum::STUDENT->value:

            break;
            case UsersRolesTrainingsEnum::SCHOLARSHIP_MANAGER->value:
                $res[] = 'TRAINING_PARAMETERS|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_PARAMETERS_LESSON_SESSION|ALL|'.$this->getTraining()->getId();
                
                $res[] = 'TRAINING_REPORTING|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_SCHOLARSHIP|READ|'.$this->getTraining()->getId();
                
                $res[] = 'TRAINING_EXPORTS_SIGNATURE|READ|'.$this->getTraining()->getId();
            break;
            case UsersRolesTrainingsEnum::PEDAGOGIC_MANAGER->value:
                $res[] = 'TRAINING_PARAMETERS|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_PARAMETERS_OPTION|ALL|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_PARAMETERS_TIMESLOT|ALL|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_PARAMETERS_TOPIC_GROUP|ALL|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_PARAMETERS_TOPIC|ALL|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_PARAMETERS_LESSON_SESSION|ALL|'.$this->getTraining()->getId();
                
                $res[] = 'TRAINING_REPORTING|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_SCHOLARSHIP|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_PEDAGOGIC|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_FINANCIAL|READ|'.$this->getTraining()->getId();

                $res[] = 'TRAINING_EXPORTS_SIGNATURE|READ|'.$this->getTraining()->getId();
            break;
            case UsersRolesTrainingsEnum::ADMINISTRATIVE_MANAGER->value:
                $res[] = 'TRAINING_PARAMETERS|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_PARAMETERS_FINANCIAL|ALL|'.$this->getTraining()->getId();
                
                $res[] = 'TRAINING_REPORTING|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_FINANCIAL|READ|'.$this->getTraining()->getId();

                $res[] = 'TRAINING_EXPORTS_SIGNATURE|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_EXPORTS_INVOICING|ALL|'.$this->getTraining()->getId();
            break;
            case UsersRolesTrainingsEnum::STAFF->value:
                $res[] = 'TRAINING_REPORTING|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_SCHOLARSHIP|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_PEDAGOGIC|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_REPORTING_FINANCIAL|READ|'.$this->getTraining()->getId();
                
                $res[] = 'TRAINING_EXPORTS_SIGNATURE|READ|'.$this->getTraining()->getId();
                $res[] = 'TRAINING_EXPORTS_INVOICING|READ|'.$this->getTraining()->getId();
            break;
            case UsersRolesTrainingsEnum::ADMIN->value:
                $res[] = 'TRAINING_ALL|ALL|'.$this->getTraining()->getId();
            break;
            default:
        }
        return $res;
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

    /**
     * @return Collection<int, TrainingsOptions>
     */
    public function getTrainingOptions(): Collection
    {
        return $this->trainingOptions;
    }

    public function addTrainingOption(TrainingsOptions $trainingOption): static
    {
        if (!$this->trainingOptions->contains($trainingOption)) {
            $this->trainingOptions->add($trainingOption);
        }

        return $this;
    }

    public function removeTrainingOption(TrainingsOptions $trainingOption): static
    {
        $this->trainingOptions->removeElement($trainingOption);

        return $this;
    }
}
