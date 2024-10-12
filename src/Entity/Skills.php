<?php

namespace App\Entity;

use App\Repository\SkillsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillsRepository::class)]
class Skills
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $cursus_order = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $short_name = null;

    /**
     * @var Collection<int, LessonSessions>
     */
    #[ORM\ManyToMany(targetEntity: LessonSessions::class, inversedBy: 'skills')]
    private Collection $lesson_sessions;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: true)]
    private ?TopicsGroups $topics_group = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    public function __construct()
    {
        $this->lesson_sessions = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCursusOrder(): ?int
    {
        return $this->cursus_order;
    }

    public function setCursusOrder(?int $cursus_order): static
    {
        $this->cursus_order = $cursus_order;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(?string $short_name): static
    {
        $this->short_name = $short_name;

        return $this;
    }

    /**
     * @return Collection<int, LessonSessions>
     */
    public function getLessonSessions(): Collection
    {
        return $this->lesson_sessions;
    }

    public function addLessonSession(LessonSessions $lessonSession): static
    {
        if (!$this->lesson_sessions->contains($lessonSession)) {
            $this->lesson_sessions->add($lessonSession);
        }

        return $this;
    }

    public function removeLessonSession(LessonSessions $lessonSession): static
    {
        $this->lesson_sessions->removeElement($lessonSession);

        return $this;
    }

    public function getTopicsGroup(): ?TopicsGroups
    {
        return $this->topics_group;
    }

    public function setTopicsGroup(?TopicsGroups $topics_group): static
    {
        $this->topics_group = $topics_group;

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
}
