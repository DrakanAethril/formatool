<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TopicsTrainingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicsTrainingsRepository::class)]
#[ApiResource]
class TopicsTrainings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cm = null;

    #[ORM\Column]
    private ?int $td = null;

    #[ORM\Column]
    private ?int $tp = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Topics $topics = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trainings $trainings = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCm(): ?int
    {
        return $this->cm;
    }

    public function setCm(int $cm): static
    {
        $this->cm = $cm;

        return $this;
    }

    public function getTd(): ?int
    {
        return $this->td;
    }

    public function setTd(int $td): static
    {
        $this->td = $td;

        return $this;
    }

    public function getTp(): ?int
    {
        return $this->tp;
    }

    public function setTp(int $tp): static
    {
        $this->tp = $tp;

        return $this;
    }

    public function getTopics(): ?Topics
    {
        return $this->topics;
    }

    public function setTopics(?Topics $topics): static
    {
        $this->topics = $topics;

        return $this;
    }

    public function getTrainings(): ?Trainings
    {
        return $this->trainings;
    }

    public function setTrainings(?Trainings $trainings): static
    {
        $this->trainings = $trainings;

        return $this;
    }
}
