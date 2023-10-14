<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TrainingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingsRepository::class)]
#[ApiResource]
class Trainings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $level = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Places $place = null;

    #[ORM\ManyToMany(targetEntity: Topics::class, mappedBy: 'trainings')]
    private Collection $topics;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }

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

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

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
     * @return Collection<int, Topics>
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topics $topic): static
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->addTraining($this);
        }

        return $this;
    }

    public function removeTopic(Topics $topic): static
    {
        if ($this->topics->removeElement($topic)) {
            $topic->removeTraining($this);
        }

        return $this;
    }

    public function __toString() : string {
        return $this->getTitle();
    }
}
