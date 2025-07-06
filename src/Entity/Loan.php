<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\EntityListeners;
use App\EventListener\LoanListener;


#[EntityListeners([LoanListener::class])]
#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $returnDate = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;


    #[ORM\ManyToOne(inversedBy: 'loans')]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'borrowed')]
    private ?User $borrower = null;

    #[ORM\ManyToOne(inversedBy: 'lent')]
    private ?User $lender = null;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTime
    {
        return $this->returnDate;
    }

    public function setReturnDate(\DateTime $returnDate): static
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;
        return $this;
    }

    public function getBorrower(): ?User
    {
        return $this->borrower;
    }

    public function setBorrower(?User $borrower): static
    {
        $this->borrower = $borrower;

        return $this;
    }

    public function getLender(): ?User
    {
        return $this->lender;
    }

    public function setLender(?User $lender): static
    {
        $this->lender = $lender;

        return $this;
    }

}
