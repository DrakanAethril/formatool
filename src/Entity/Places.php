<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlacesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlacesRepository::class)]
#[ApiResource]
class Places
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\OneToMany(mappedBy: 'place', targetEntity: Trainings::class)]
    private Collection $trainings;

    #[ORM\OneToMany(targetEntity: ClassRooms::class, mappedBy: 'place')]
    private Collection $classRooms;

    #[ORM\ManyToMany(targetEntity: Cursus::class, mappedBy: 'places')]
    private Collection $cursuses;

    /**
     * @var Collection<int, UsersPlaces>
     */
    #[ORM\OneToMany(targetEntity: UsersPlaces::class, mappedBy: 'place')]
    private Collection $usersPlaces;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
        $this->classRooms = new ArrayCollection();
        $this->cursuses = new ArrayCollection();
        $this->usersPlaces = new ArrayCollection();
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
            $training->setPlace($this);
        }

        return $this;
    }

    public function removeTraining(Trainings $training): static
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getPlace() === $this) {
                $training->setPlace(null);
            }
        }

        return $this;
    }

    public function __toString() : string {
        return $this->getName();
    }

    /**
     * @return Collection<int, ClassRooms>
     */
    public function getClassRooms(): Collection
    {
        return $this->classRooms;
    }

    public function addClassRoom(ClassRooms $classRoom): static
    {
        if (!$this->classRooms->contains($classRoom)) {
            $this->classRooms->add($classRoom);
            $classRoom->setPlace($this);
        }

        return $this;
    }

    public function removeClassRoom(ClassRooms $classRoom): static
    {
        if ($this->classRooms->removeElement($classRoom)) {
            // set the owning side to null (unless already changed)
            if ($classRoom->getPlace() === $this) {
                $classRoom->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cursus>
     */
    public function getCursuses(): Collection
    {
        return $this->cursuses;
    }

    public function addCursus(Cursus $cursus): static
    {
        if (!$this->cursuses->contains($cursus)) {
            $this->cursuses->add($cursus);
            $cursus->addPlace($this);
        }

        return $this;
    }

    public function removeCursus(Cursus $cursus): static
    {
        if ($this->cursuses->removeElement($cursus)) {
            $cursus->removePlace($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UsersPlaces>
     */
    public function getUsersPlaces(): Collection
    {
        return $this->usersPlaces;
    }

    public function addUsersPlace(UsersPlaces $usersPlace): static
    {
        if (!$this->usersPlaces->contains($usersPlace)) {
            $this->usersPlaces->add($usersPlace);
            $usersPlace->setPlace($this);
        }

        return $this;
    }

    public function removeUsersPlace(UsersPlaces $usersPlace): static
    {
        if ($this->usersPlaces->removeElement($usersPlace)) {
            // set the owning side to null (unless already changed)
            if ($usersPlace->getPlace() === $this) {
                $usersPlace->setPlace(null);
            }
        }

        return $this;
    }
}
