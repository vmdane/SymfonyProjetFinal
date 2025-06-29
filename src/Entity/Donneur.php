<?php

namespace App\Entity;

use App\Repository\DonneurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonneurRepository::class)]
class Donneur
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
