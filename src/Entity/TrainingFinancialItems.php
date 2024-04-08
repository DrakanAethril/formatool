<?php

namespace App\Entity;

use App\Repository\TrainingFinancialItemsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Config\FinancialItemsTypeEnum;
use App\Config\FinancialItemsSourceEnum;

#[ORM\Entity(repositoryClass: TrainingFinancialItemsRepository::class)]
class TrainingFinancialItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $source = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $value = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\ManyToOne(inversedBy: 'trainingFinancialItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trainings $training = null;

    #[ORM\ManyToOne(inversedBy: 'trainingFinancialItems')]
    private ?LessonTypes $lessonType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSource(): ?int
    {
        return $this->source;
    }

    public function setSource(int $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

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

    public function getLessonType(): ?LessonTypes
    {
        return $this->lessonType;
    }

    public function setLessonType(?LessonTypes $lessonType): static
    {
        $this->lessonType = $lessonType;

        return $this;
    }

    public function getEnumTypeObject() {
        return FinancialItemsTypeEnum::tryFrom($this->getType());
    }

    public function getEnumSourceObject() {
        return FinancialItemsSourceEnum::tryFrom($this->getSource());
    }

    public function getStudentsNumber():int {
        return 8;
    }

    public function getLessonsHours():int {
        return 10;
    }
}
