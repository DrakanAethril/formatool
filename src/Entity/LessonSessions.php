<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LessonSessionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonSessionsRepository::class)]
#[ApiResource]
class LessonSessions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $startHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $endHour = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $length = null;

    #[ORM\ManyToOne(inversedBy: 'lessonSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trainings $training = null;

    #[ORM\ManyToOne(inversedBy: 'lessonSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TopicsTrainings $topic = null;

    #[ORM\ManyToOne(inversedBy: 'lessonSessions')]
    private ?Users $teacher = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'lessonSessions')]
    private ?ClassRooms $classRooms = null;

    #[ORM\ManyToOne(inversedBy: 'lessonSessions')]
    private ?LessonTypes $lessonType = null;

    /**
     * @var Collection<int, TrainingsOptions>
     */
    #[ORM\ManyToMany(targetEntity: TrainingsOptions::class, inversedBy: 'lessonSessions')]
    private Collection $trainingOptions;

    public function __construct()
    {
        $this->trainingOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->startHour;
    }

    public function setStartHour(\DateTimeInterface $startHour): static
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getEndHour(): ?\DateTimeInterface
    {
        return $this->endHour;
    }

    public function setEndHour(\DateTimeInterface $endHour): static
    {
        $this->endHour = $endHour;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(string $length): static
    {
        $this->length = $length;

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

    public function getTopic(): ?TopicsTrainings
    {
        return $this->topic;
    }

    public function setTopic(?TopicsTrainings $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function getTeacher(): ?Users
    {
        return $this->teacher;
    }

    public function setTeacher(?Users $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDisplayName() {
        return empty($this->getTitle()) ? $this->getTopic()->getTopics()->getName() : $this->getTitle();
    }

    public function getClassRooms(): ?ClassRooms
    {
        return $this->classRooms;
    }

    public function setClassRooms(?ClassRooms $classRooms): static
    {
        $this->classRooms = $classRooms;

        return $this;
    }

    public function getLessonType(): ?LessonTypes
    {
        return $this->lessonType;
    }

    public function setLessonType(?LessonTypes $lessonType): static
    {
        $this->lessonType = $lessonType;

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
