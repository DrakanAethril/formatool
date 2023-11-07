<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TimeSlotsTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeSlotsTypesRepository::class)]
#[ApiResource]
class TimeSlotsTypes
{
    public const LESSONS_TYPE = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\OneToMany(mappedBy: 'timeSlotsTypes', targetEntity: TimeSlots::class)]
    private Collection $timeSlots;

    #[ORM\Column(length: 20)]
    private ?string $color = null;

    public function __construct()
    {
        $this->timeSlots = new ArrayCollection();
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
     * @return Collection<int, TimeSlots>
     */
    public function getTimeSlots(): Collection
    {
        return $this->timeSlots;
    }

    public function addTimeSlot(TimeSlots $timeSlot): static
    {
        if (!$this->timeSlots->contains($timeSlot)) {
            $this->timeSlots->add($timeSlot);
            $timeSlot->setTimeSlotsTypes($this);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlots $timeSlot): static
    {
        if ($this->timeSlots->removeElement($timeSlot)) {
            // set the owning side to null (unless already changed)
            if ($timeSlot->getTimeSlotsTypes() === $this) {
                $timeSlot->setTimeSlotsTypes(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->name;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public static function getSupportedLessonsType(): array {
        return  ['Cm', 'Td', 'Tp'];
    }
}
