<?php

namespace App\Entity;

use App\Repository\AvailabilityRateDaysRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;


#[ApiResource]
#[ORM\Entity(repositoryClass: AvailabilityRateDaysRepository::class)]
class AvailabilityRateDays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['availability_rate'])]
    private ?int $id = null;

    #[Groups(['availability_rate'])]
    #[ORM\Column(length: 255)]
    private ?string $service = null;

    #[ORM\Column]
    #[Groups(['availability_rate'])]
    private ?float $rate = null;

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

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}
