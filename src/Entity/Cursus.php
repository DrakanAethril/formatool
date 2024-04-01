<?php

namespace App\Entity;

use App\Repository\CursusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CursusRepository::class)]
class Cursus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\ManyToOne(inversedBy: 'cursuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CursusType $type = null;

    #[ORM\OneToMany(targetEntity: Trainings::class, mappedBy: 'cursus')]
    private Collection $trainings;

    #[ORM\ManyToMany(targetEntity: Places::class, inversedBy: 'cursuses')]
    private Collection $places;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
        $this->places = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    public function getInactive(): ?\DateTimeInterface
    {
        return $this->inactive;
    }

    public function setInactive(?\DateTimeInterface $inactive): static
    {
        $this->inactive = $inactive;

        return $this;
    }

    public function getType(): ?CursusType
    {
        return $this->type;
    }

    public function setType(?CursusType $type): static
    {
        $this->type = $type;

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
            $training->setCursus($this);
        }

        return $this;
    }

    public function removeTraining(Trainings $training): static
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getCursus() === $this) {
                $training->setCursus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Places>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Places $place): static
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
        }

        return $this;
    }

    public function removePlace(Places $place): static
    {
        $this->places->removeElement($place);

        return $this;
    }
}
