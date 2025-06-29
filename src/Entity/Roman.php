<?php

namespace App\Entity;

use App\Repository\RomanRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RomanRepository::class)]
class Roman
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
