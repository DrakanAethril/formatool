<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TopicsTrainingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\ManyToMany(targetEntity: TopicsTrainingsLabel::class, mappedBy: 'topicsTrainings')]
    private Collection $topicsTrainingsLabels;

    #[ORM\ManyToOne(inversedBy: 'topicsTrainings')]
    private ?TopicsGroups $topicsGroups = null;

    #[ORM\ManyToOne(inversedBy: 'teachingTopics')]
    private ?Users $teacher = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->topicsTrainingsLabels = new ArrayCollection();
    }

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

    public function getTotalVolume() : int {
        return intval($this->getCm()+ $this->getTd() + $this->getTp());
    }

    /**
     * @return Collection<int, TopicsTrainingsLabel>
     */
    public function getTopicsTrainingsLabels(): Collection
    {
        return $this->topicsTrainingsLabels;
    }

    public function addTopicsTrainingsLabel(TopicsTrainingsLabel $topicsTrainingsLabel): static
    {
        if (!$this->topicsTrainingsLabels->contains($topicsTrainingsLabel)) {
            $this->topicsTrainingsLabels->add($topicsTrainingsLabel);
            $topicsTrainingsLabel->addTopicsTraining($this);
        }

        return $this;
    }

    public function removeTopicsTrainingsLabel(TopicsTrainingsLabel $topicsTrainingsLabel): static
    {
        if ($this->topicsTrainingsLabels->removeElement($topicsTrainingsLabel)) {
            $topicsTrainingsLabel->removeTopicsTraining($this);
        }

        return $this;
    }

    public function getTopicsGroups(): ?TopicsGroups
    {
        return $this->topicsGroups;
    }

    public function setTopicsGroups(?TopicsGroups $topicsGroups): static
    {
        $this->topicsGroups = $topicsGroups;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
