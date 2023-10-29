<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TimeSlotsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeSlotsRepository::class)]
#[ApiResource]
class TimeSlots
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\ManyToOne(inversedBy: 'timeSlots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trainings $training = null;

    #[ORM\ManyToOne(inversedBy: 'timeSlots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TimeSlotsTypes $timeSlotsTypes = null;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

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

    public function getTimeSlotsTypes(): ?TimeSlotsTypes
    {
        return $this->timeSlotsTypes;
    }

    public function setTimeSlotsTypes(?TimeSlotsTypes $timeSlotsTypes): static
    {
        $this->timeSlotsTypes = $timeSlotsTypes;

        return $this;
    }
}
