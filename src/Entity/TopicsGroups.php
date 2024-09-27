<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TopicsGroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicsGroupsRepository::class)]
#[ApiResource]
class TopicsGroups
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\OneToMany(mappedBy: 'topicsGroups', targetEntity: TopicsTrainings::class)]
    private Collection $topicsTrainings;

    #[ORM\ManyToOne(inversedBy: 'topicsGroups')]
    private ?Cursus $cursus = null;

    public function __construct()
    {
        $this->topicsTrainings = new ArrayCollection();
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

    /**
     * @return Collection<int, TopicsTrainings>
     */
    public function getTopicsTrainings(): Collection
    {
        return $this->topicsTrainings;
    }

    public function addTopicsTraining(TopicsTrainings $topicsTraining): static
    {
        if (!$this->topicsTrainings->contains($topicsTraining)) {
            $this->topicsTrainings->add($topicsTraining);
            $topicsTraining->setTopicsGroups($this);
        }

        return $this;
    }

    public function removeTopicsTraining(TopicsTrainings $topicsTraining): static
    {
        if ($this->topicsTrainings->removeElement($topicsTraining)) {
            // set the owning side to null (unless already changed)
            if ($topicsTraining->getTopicsGroups() === $this) {
                $topicsTraining->setTopicsGroups(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->getName();
    }

    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }
}
