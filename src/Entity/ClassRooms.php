<?php

namespace App\Entity;

use App\Repository\ClassRoomsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassRoomsRepository::class)]
class ClassRooms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\ManyToOne(inversedBy: 'classRooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Places $place = null;

    #[ORM\ManyToMany(targetEntity: Trainings::class, inversedBy: 'classRooms')]
    private Collection $trainings;

    #[ORM\OneToMany(targetEntity: LessonSessions::class, mappedBy: 'classRooms')]
    private Collection $lessonSessions;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
        $this->lessonSessions = new ArrayCollection();
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

    public function getPlace(): ?Places
    {
        return $this->place;
    }

    public function setPlace(?Places $place): static
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return Collection<int, Trainings>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Trainings $training): static
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
        }

        return $this;
    }

    public function removeTraining(Trainings $training): static
    {
        $this->trainings->removeElement($training);

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
            $lessonSession->setClassRooms($this);
        }

        return $this;
    }

    public function removeLessonSession(LessonSessions $lessonSession): static
    {
        if ($this->lessonSessions->removeElement($lessonSession)) {
            // set the owning side to null (unless already changed)
            if ($lessonSession->getClassRooms() === $this) {
                $lessonSession->setClassRooms(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return $this->getName();
    }
}
