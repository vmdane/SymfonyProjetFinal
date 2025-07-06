<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column]
    private ?\DateTime $createAt = null;




    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'user')]
    private Collection $reviews;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'user')]
    private Collection $notifications;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class)]
    #[ORM\JoinTable(name: 'user_favorite')]
    private $favorite;

    /**
     * @var Collection<int, Loan>
     */
    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'borrower')]
    private Collection $borrowed;

    /**
     * @var Collection<int, Loan>
     */
    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'lender')]
    private Collection $lent;

    /**
     * @var Collection<int, Bookshelf>
     */
    #[ORM\OneToMany(targetEntity: Bookshelf::class, mappedBy: 'user')]
    private Collection $bookshelves;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->favorite = new ArrayCollection();
        $this->borrowed = new ArrayCollection();
        $this->lent = new ArrayCollection();
        $this->bookshelves = new ArrayCollection();
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

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCreateAt(): ?\DateTime
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTime $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }






    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Book $book): self
    {
        if (!$this->favorite->contains($book)) {
            $this->favorite[] = $book;
        }

        return $this;
    }

    public function removeFavorite(Book $book): self
    {
        $this->favorite->removeElement($book);

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getBorrowed(): Collection
    {
        return $this->borrowed;
    }

    public function addBorrowed(Loan $borrowed): static
    {
        if (!$this->borrowed->contains($borrowed)) {
            $this->borrowed->add($borrowed);
            $borrowed->setBorrower($this);
        }

        return $this;
    }

    public function removeBorrowed(Loan $borrowed): static
    {
        if ($this->borrowed->removeElement($borrowed)) {
            // set the owning side to null (unless already changed)
            if ($borrowed->getBorrower() === $this) {
                $borrowed->setBorrower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getLent(): Collection
    {
        return $this->lent;
    }

    public function addLent(Loan $lent): static
    {
        if (!$this->lent->contains($lent)) {
            $this->lent->add($lent);
            $lent->setLender($this);
        }

        return $this;
    }

    public function removeLent(Loan $lent): static
    {
        if ($this->lent->removeElement($lent)) {
            // set the owning side to null (unless already changed)
            if ($lent->getLender() === $this) {
                $lent->setLender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bookshelf>
     */
    public function getLibraries(): Collection
    {
        return $this->bookshelves;
    }

    public function addLibrary(Bookshelf $library): static
    {
        if (!$this->bookshelves->contains($library)) {
            $this->bookshelves->add($library);
            $library->setUser($this);
        }

        return $this;
    }

    public function removeLibrary(Bookshelf $library): static
    {
        if ($this->bookshelves->removeElement($library)) {
            // set the owning side to null (unless already changed)
            if ($library->getUser() === $this) {
                $library->setUser(null);
            }
        }

        return $this;
    }

}
