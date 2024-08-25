<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TrainingsOptionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingsOptionsRepository::class)]
#[ApiResource]
class TrainingsOptions
{
    public const DEFAULT_PUBLIC_AGENDA_COLOR = '#0054A6';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\ManyToOne(inversedBy: 'trainingsOptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trainings $training = null;

    #[ORM\Column(length: 50)]
    private ?string $shortname = null;

    /**
     * @var Collection<int, LessonSessions>
     */
    #[ORM\ManyToMany(targetEntity: LessonSessions::class, mappedBy: 'trainingOptions')]
    private Collection $lessonSessions;

    /**
     * @var Collection<int, UsersTrainings>
     */
    #[ORM\ManyToMany(targetEntity: UsersTrainings::class, mappedBy: 'trainingOptions')]
    private Collection $usersTrainings;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agendaColor = null;

    public function __construct()
    {
        $this->lessonSessions = new ArrayCollection();
        $this->usersTrainings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getInactive(): ?\DateTimeInterface
    {
        return $this->inactive;
    }

    public function setInactive(?\DateTimeInterface $inactive): static
    {
        $this->inactive = $inactive;

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

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): static
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * @return Collection<int, LessonSessions>
     */
    public function getLessonSessions(): Collection
    {
        return $this->lessonSessions;
    }

    public function addLessonSession(LessonSessions $lessonSession): static
    {
        if (!$this->lessonSessions->contains($lessonSession)) {
            $this->lessonSessions->add($lessonSession);
            $lessonSession->addTrainingOption($this);
        }

        return $this;
    }

    public function removeLessonSession(LessonSessions $lessonSession): static
    {
        if ($this->lessonSessions->removeElement($lessonSession)) {
            $lessonSession->removeTrainingOption($this);
        }

        return $this;
    }

    public function __toString(): string 
    {
        return $this->getShortname();
    }

    /**
     * @return Collection<int, UsersTrainings>
     */
    public function getUsersTrainings(): Collection
    {
        return $this->usersTrainings;
    }

    public function addUsersTraining(UsersTrainings $usersTraining): static
    {
        if (!$this->usersTrainings->contains($usersTraining)) {
            $this->usersTrainings->add($usersTraining);
            $usersTraining->addTrainingOption($this);
        }

        return $this;
    }

    public function removeUsersTraining(UsersTrainings $usersTraining): static
    {
        if ($this->usersTrainings->removeElement($usersTraining)) {
            $usersTraining->removeTrainingOption($this);
        }

        return $this;
    }

    public function getAgendaColor(): ?string
    {
        return $this->agendaColor;
    }

    public function setAgendaColor(?string $agendaColor): static
    {
        $this->agendaColor = $agendaColor;

        return $this;
    }
}
