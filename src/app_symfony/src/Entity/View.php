<?php

namespace App\Entity;

use App\Repository\ViewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViewRepository::class)]
class View
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $linkId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $ts = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkId(): ?int
    {
        return $this->linkId;
    }

    public function setLinkId(int $linkId): static
    {
        $this->linkId = $linkId;

        return $this;
    }

    public function getTs(): ?\DateTimeInterface
    {
        return $this->ts;
    }

    public function setTs(\DateTimeInterface $ts): static
    {
        $this->ts = $ts;

        return $this;
    }
}
