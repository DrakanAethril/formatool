<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Ignore;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleted = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Trainings::class)]
    private Collection $ownedTrainings;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(nullable: true)]
    private ?string $avatarName = null;

    #[ORM\Column(nullable: true)]
    private ?int $avatarSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Vich\UploadableField(mapping: 'avatars', fileNameProperty: 'avatarName', size: 'avatarSize')]
    #[Ignore]
    private ?File $avatarFile = null;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: TopicsTrainings::class)]
    private Collection $teachingTopics;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: LessonSessions::class)]
    private Collection $lessonSessions;

    public function __construct()
    {
        $this->ownedTrainings = new ArrayCollection();
        $this->teachingTopics = new ArrayCollection();
        $this->lessonSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?\DateTimeInterface $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getDisplayName() : ?string {
        return $this->getFirstname()." ".$this->getLastName();
    }

    public function __toString() : string {
        return $this->getDisplayName();
    }

    /**
     * @return Collection<int, Trainings>
     */
    public function getOwnedTrainings(): Collection
    {
        return $this->ownedTrainings;
    }

    public function addOwnedTraining(Trainings $ownedTraining): static
    {
        if (!$this->ownedTrainings->contains($ownedTraining)) {
            $this->ownedTrainings->add($ownedTraining);
            $ownedTraining->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedTraining(Trainings $ownedTraining): static
    {
        if ($this->ownedTrainings->removeElement($ownedTraining)) {
            // set the owning side to null (unless already changed)
            if ($ownedTraining->getOwner() === $this) {
                $ownedTraining->setOwner(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function setAvatarFile(?File $imageFile = null): void
    {
        $this->avatarFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarName(?string $imageName): void
    {
        $this->avatarName = $imageName;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarSize(?int $imageSize): void
    {
        $this->avatarSize = $imageSize;
    }

    public function getAvatarSize(): ?int
    {
        return $this->avatarSize;
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    /**
     * Restore security relevant data
     *
     * @param array $data
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public function getAvatarUrl(): ?string {
        if(!empty($this->getAvatarName())) {
            $avatar = 'uploads/avatars/'.$this->getAvatarName();
        } else {
            $avatar = $this->getDefaultAvatar();
        }
        return $avatar;
    }
    
    public function getDefaultAvatar(): ?string {
        return 'static/avatars/default.png';
    }

    /**
     * @return Collection<int, TopicsTrainings>
     */
    public function getTeachingTopics(): Collection
    {
        return $this->teachingTopics;
    }

    public function addTeachingTopic(TopicsTrainings $teachingTopic): static
    {
        if (!$this->teachingTopics->contains($teachingTopic)) {
            $this->teachingTopics->add($teachingTopic);
            $teachingTopic->setTeacher($this);
        }

        return $this;
    }

    public function removeTeachingTopic(TopicsTrainings $teachingTopic): static
    {
        if ($this->teachingTopics->removeElement($teachingTopic)) {
            // set the owning side to null (unless already changed)
            if ($teachingTopic->getTeacher() === $this) {
                $teachingTopic->setTeacher(null);
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
            $lessonSession->setTeacher($this);
        }

        return $this;
    }

    public function removeLessonSession(LessonSessions $lessonSession): static
    {
        if ($this->lessonSessions->removeElement($lessonSession)) {
            // set the owning side to null (unless already changed)
            if ($lessonSession->getTeacher() === $this) {
                $lessonSession->setTeacher(null);
            }
        }

        return $this;
    }
}
