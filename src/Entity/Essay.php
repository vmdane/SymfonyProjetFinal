<?php

namespace App\Entity;

use App\Repository\EssayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EssayRepository::class)]
class Essay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
