<?php

namespace App\Entity;

use App\Repository\NovelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NovelRepository::class)]
class Novel
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
