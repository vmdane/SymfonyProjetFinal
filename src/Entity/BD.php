<?php

namespace App\Entity;

use App\Repository\BDRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BDRepository::class)]
class BD
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
