<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Config\UsersRolesTrainingsEnum;
use App\Config\UsersStatusTrainingsEnum;
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inactive = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Places $place = null;

    #[ORM\OneToMany(mappedBy: 'trainings', targetEntity: TopicsTrainings::class)]
    private Collection $trainings;

    #[ORM\OneToMany(mappedBy: 'training', targetEntity: TimeSlots::class)]
    private Collection $timeSlots;

    #[ORM\OneToMany(mappedBy: 'training', targetEntity: LessonSessions::class)]
    private Collection $lessonSessions;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    private ?Cursus $cursus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortTitle = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    private ?TrainingsModality $trainingsModality = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startTrainingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endTrainingDate = null;

    #[ORM\ManyToOne(inversedBy: 'trainingsContentContact')]
    private ?Users $contentContact = null;

    #[ORM\ManyToOne(inversedBy: 'trainingsScholarshipContact')]
    private ?Users $scholarshipContact = null;

    #[ORM\ManyToOne(inversedBy: 'trainingsAdministrativeContact')]
    private ?Users $administrativeContact = null;

    #[ORM\Column(nullable: true)]
    private ?bool $activateFinancialManagement = null;

    #[ORM\ManyToOne(inversedBy: 'trainingsDefaultClassRoom')]
    private ?ClassRooms $defaultClassRoom = null;

    #[ORM\OneToMany(targetEntity: TrainingFinancialItems::class, mappedBy: 'training')]
    private Collection $trainingFinancialItems;

    /**
     * @var Collection<int, UsersTrainings>
     */
    #[ORM\OneToMany(targetEntity: UsersTrainings::class, mappedBy: 'training')]
    private Collection $usersTrainings;

    /**
     * @var Collection<int, TrainingsOptions>
     */
    #[ORM\OneToMany(targetEntity: TrainingsOptions::class, mappedBy: 'training')]
    private Collection $trainingsOptions;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
        $this->timeSlots = new ArrayCollection();
        $this->lessonSessions = new ArrayCollection();
        $this->trainingFinancialItems = new ArrayCollection();
        $this->usersTrainings = new ArrayCollection();
        $this->trainingsOptions = new ArrayCollection();
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

    public function getLevel(): string
    {
        if(!empty($this->getCursus())) {
            return $this->getCursus()->getType()->getLevel();
        }
        return '';
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

    public function __toString() : string {
        return $this->getTitle();
    }

    /**
     * @return Collection<int, TopicsTrainings>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(TopicsTrainings $training): static
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
            $training->setTrainings($this);
        }

        return $this;
    }

    public function removeTraining(TopicsTrainings $training): static
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getTrainings() === $this) {
                $training->setTrainings(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TopicsGroups>
     */
    public function getTopicsGroups(): Collection
    {
        return $this->getCursus()->getTopicsGroups();
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
            $timeSlot->setTraining($this);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlots $timeSlot): static
    {
        if ($this->timeSlots->removeElement($timeSlot)) {
            // set the owning side to null (unless already changed)
            if ($timeSlot->getTraining() === $this) {
                $timeSlot->setTraining(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LessonSessions>
     */
    public function getLessonSessions(): Collection
    {
        return $this->lessonSessions;
    }

    public function addLessonSession(LessonSessions $lessonSession): static
    {
        if (!$this->lessonSessions->contains($lessonSession)) {
            $this->lessonSessions->add($lessonSession);
            $lessonSession->setTraining($this);
        }

        return $this;
    }

    public function removeLessonSession(LessonSessions $lessonSession): static
    {
        if ($this->lessonSessions->removeElement($lessonSession)) {
            // set the owning side to null (unless already changed)
            if ($lessonSession->getTraining() === $this) {
                $lessonSession->setTraining(null);
            }
        }

        return $this;
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

    public function getShortTitle(): ?string
    {
        return $this->shortTitle;
    }

    public function setShortTitle(?string $shortTitle): static
    {
        $this->shortTitle = $shortTitle;

        return $this;
    }

    public function getShortDisplayName() : string {
        return empty($this->getShortTitle()) ? $this->getTitle() : $this->getShortTitle();
    }

    public function getTrainingsModality(): ?TrainingsModality
    {
        return $this->trainingsModality;
    }

    public function setTrainingsModality(?TrainingsModality $trainingsModality): static
    {
        $this->trainingsModality = $trainingsModality;

        return $this;
    }

    public function getStartTrainingDate(): ?\DateTimeInterface
    {
        return $this->startTrainingDate;
    }

    public function setStartTrainingDate(?\DateTimeInterface $startTrainingDate): static
    {
        $this->startTrainingDate = $startTrainingDate;

        return $this;
    }

    public function getEndTrainingDate(): ?\DateTimeInterface
    {
        return $this->endTrainingDate;
    }

    public function setEndTrainingDate(?\DateTimeInterface $endTrainingDate): static
    {
        $this->endTrainingDate = $endTrainingDate;

        return $this;
    }

    public function getContentContact(): ?Users
    {
        return $this->contentContact;
    }

    public function setContentContact(?Users $contentContact): static
    {
        $this->contentContact = $contentContact;

        return $this;
    }

    public function getScholarshipContact(): ?Users
    {
        return $this->scholarshipContact;
    }

    public function setScholarshipContact(?Users $scholarshipContact): static
    {
        $this->scholarshipContact = $scholarshipContact;

        return $this;
    }

    public function getAdministrativeContact(): ?Users
    {
        return $this->administrativeContact;
    }

    public function setAdministrativeContact(?Users $administrativeContact): static
    {
        $this->administrativeContact = $administrativeContact;

        return $this;
    }

    public function isActivateFinancialManagement(): ?bool
    {
        return $this->activateFinancialManagement;
    }

    public function setActivateFinancialManagement(?bool $activateFinancialManagement): static
    {
        $this->activateFinancialManagement = $activateFinancialManagement;

        return $this;
    }

    public function getDefaultClassRoom(): ?ClassRooms
    {
        return $this->defaultClassRoom;
    }

    public function setDefaultClassRoom(?ClassRooms $defaultClassRoom): static
    {
        $this->defaultClassRoom = $defaultClassRoom;

        return $this;
    }

    /**
     * @return Collection<int, TrainingFinancialItems>
     */
    public function getTrainingFinancialItems(): Collection
    {
        return $this->trainingFinancialItems;
    }

    public function addTrainingFinancialItem(TrainingFinancialItems $trainingFinancialItem): static
    {
        if (!$this->trainingFinancialItems->contains($trainingFinancialItem)) {
            $this->trainingFinancialItems->add($trainingFinancialItem);
            $trainingFinancialItem->setTraining($this);
        }

        return $this;
    }

    public function removeTrainingFinancialItem(TrainingFinancialItems $trainingFinancialItem): static
    {
        if ($this->trainingFinancialItems->removeElement($trainingFinancialItem)) {
            // set the owning side to null (unless already changed)
            if ($trainingFinancialItem->getTraining() === $this) {
                $trainingFinancialItem->setTraining(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UsersTrainings>
     */
    public function getUsersTrainings(): Collection
    {
        return $this->usersTrainings;
    }

    public function addUsersTraining(UsersTrainings $usersTraining): static
    {
        if (!$this->usersTrainings->contains($usersTraining)) {
            $this->usersTrainings->add($usersTraining);
            $usersTraining->setTraining($this);
        }

        return $this;
    }

    public function removeUsersTraining(UsersTrainings $usersTraining): static
    {
        if ($this->usersTrainings->removeElement($usersTraining)) {
            // set the owning side to null (unless already changed)
            if ($usersTraining->getTraining() === $this) {
                $usersTraining->setTraining(null);
            }
        }

        return $this;
    }

    public function getFinancialEligibleStudents() : int {
        $nbStudents = 0;
        $students = $this->getUsersTrainings();
        if(!empty($students)) {
            foreach($students as $student) {
                if(!in_array(UsersRolesTrainingsEnum::STUDENT->value, $student->getRoles()) ) continue;
                if(
                    $student->getStatus() == UsersStatusTrainingsEnum::ACTIVE->value || 
                    $student->getStatus() == UsersStatusTrainingsEnum::WAITING_CONTRACT->value
                ) {
                    $nbStudents++;
                }
            }
        }
        return $nbStudents;
    }

    /**
     * @return Collection<int, TrainingsOptions>
     */
    public function getTrainingsOptions(): Collection
    {
        return $this->trainingsOptions;
    }

    public function addTrainingsOption(TrainingsOptions $trainingsOption): static
    {
        if (!$this->trainingsOptions->contains($trainingsOption)) {
            $this->trainingsOptions->add($trainingsOption);
            $trainingsOption->setTraining($this);
        }

        return $this;
    }

    public function removeTrainingsOption(TrainingsOptions $trainingsOption): static
    {
        if ($this->trainingsOptions->removeElement($trainingsOption)) {
            // set the owning side to null (unless already changed)
            if ($trainingsOption->getTraining() === $this) {
                $trainingsOption->setTraining(null);
            }
        }

        return $this;
    }
}
