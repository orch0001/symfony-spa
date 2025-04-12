<?php

namespace App\Entity;

use App\Repository\InterruptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: InterruptionRepository::class)]
class Interruption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['interruption'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['interruption'])]
    private ?string $service = null;

    #[ORM\Column( nullable: true)]
    #[Groups(['interruption'])]
    private ?\DateTimeImmutable $start = null;

    #[ORM\Column( nullable: true)]
    #[Groups(['interruption'])]
    private ?\DateTimeImmutable $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    public function setStart(\DateTimeImmutable $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
